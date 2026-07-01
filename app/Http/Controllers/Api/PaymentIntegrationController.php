<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Challan;
use App\Models\MedicalRequest;
use Illuminate\Support\Str;

use App\Models\Payment;
use App\Models\PaymentAuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Rules\ValidPsid;

class PaymentIntegrationController extends Controller
{
    /**
     * Bank Inquiry API
     * The bank calls this to fetch consumer info before initializing payment.
     * POST /api/payment/inquiry
     */
    public function inquiry(Request $request)
    {
        // 1. Basic Security Check
        if (!$this->isValidToken($request)) {
            return response()->json(['status' => '99', 'message' => 'Unauthorized'], 401);
        }

        // 2. Validate Consumer Number (PSID)
        // ValidPsid rule accepts both 12-digit (V2) and 20-digit (V1) PSIDs
        // and verifies Luhn checksum — version-agnostic, no hardcoded length.
        $request->validate([
            'consumer_number' => ['required', 'string', new ValidPsid()],
        ]);

        $psid = $request->consumer_number;
        $response = null;
        $category = 'Others';

        // 3. Find Record
        $challan = Challan::where('psid', $psid)->first();
        if ($challan) {
            $category = (str_contains(strtolower($challan->vehicle_type), 'bike') || str_contains(strtolower($challan->vehicle_type), 'motorcycle')) ? 'Bike' : (str_contains(strtolower($challan->vehicle_type), 'car') ? 'Car' : 'Traffic');
            
            // New Flat Structure
            $response = [
                'status' => '00',
                'message' => 'Record Found',
                'consumer_number' => $psid,
                'consumer_name' => $challan->violator_name,
                'amount_due' => (string) $challan->fine_amount,
                'amount_within_due_date' => (string) $challan->fine_amount,
                'amount_after_due_date' => (string) $challan->fine_amount,
                'created_at' => $challan->created_at->format('Ymd'),
                'due_date' => $challan->created_at->addDays(30)->format('Ymd'),
                'payment_status' => $challan->payment_status === 'paid' ? 'P' : 'U',
                'category' => $category
            ];

            /* 
            // Old Nested Structure
            $response = [
                'status' => '00',
                'message' => 'Record Found',
                'data' => [
                    'consumer_number' => $psid,
                    'consumer_name' => $challan->violator_name,
                    'amount_due' => $challan->fine_amount,
                    'amount_within_due_date' => $challan->fine_amount,
                    'amount_after_due_date' => $challan->fine_amount,
                    'billing_month' => $challan->created_at->format('Ym'),
                    'due_date' => $challan->created_at->addDays(30)->format('Ymd'),
                    'status' => $challan->payment_status === 'paid' ? 'P' : 'U'
                ]
            ];
            */
        }

        if (!$response) {
            $medical = MedicalRequest::where('psid', $psid)->first();
            if ($medical) {
                $category = 'Medical';
                $amount = $medical->amount ?? config('fees.medical', 200);
                
                // New Flat Structure
                $response = [
                    'status' => '00',
                    'message' => 'Record Found',
                    'consumer_number' => $psid,
                    'consumer_name' => $medical->citizen ? $medical->citizen->full_name : 'Citizen',
                    'amount_due' => (string) $amount,
                    'amount_within_due_date' => (string) $amount,
                    'amount_after_due_date' => (string) $amount,
                    'created_at' => $medical->created_at->format('Ymd'),
                    'due_date' => $medical->created_at->addDays(30)->format('Ymd'),
                    'payment_status' => $medical->payment_status === 'paid' ? 'P' : 'U',
                    'category' => $category
                ];

                /*
                // Old Nested Structure
                $response = [
                    'status' => '00',
                    'message' => 'Record Found',
                    'data' => [
                        'consumer_number' => $psid,
                        'consumer_name' => $medical->citizen ? $medical->citizen->full_name : 'Citizen',
                        'amount_due' => $amount,
                        'amount_within_due_date' => $amount,
                        'amount_after_due_date' => $amount,
                        'billing_month' => $medical->created_at->format('Ym'),
                        'due_date' => $medical->created_at->addDays(30)->format('Ymd'),
                        'status' => $medical->payment_status === 'paid' ? 'P' : 'U'
                    ]
                ];
                */
            }
        }

        if ($response) {
            // Log the inquiry
            $logStatus = $response['payment_status'];
            $this->logAction($psid, 'inquiry', $logStatus, $logStatus, 'Inquiry success');
            return response()->json($response);
        }

        return response()->json([
            'status' => '01',
            'message' => 'Record Not Found'
        ], 404);
    }

    /**
     * Bank Payment Notification (Webhook)
     * POST /api/payment/callback
     */
    public function paymentCallback(Request $request)
    {
        // 1. Basic Security Check
        if (!$this->isValidToken($request)) {
            return response()->json(['status' => '99', 'message' => 'Unauthorized'], 401);
        }

        // 2. Validate payload
        // ValidPsid rule accepts both 12-digit (V2) and 20-digit (V1) PSIDs
        // and verifies Luhn checksum — version-agnostic, no hardcoded length.
        $request->validate([
            'consumer_number'  => ['required', 'string', new ValidPsid()],
            'transaction_id'   => 'required|string',
            'amount_paid'      => 'required|numeric',
            'transaction_date' => 'sometimes|string',
        ]);

        $psid = $request->consumer_number;

        try {
            return DB::transaction(function () use ($psid, $request) {
                $challan = Challan::where('psid', $psid)->lockForUpdate()->first();
                $medical = null;
                $target = $challan;

                if (!$challan) {
                    $medical = MedicalRequest::where('psid', $psid)->lockForUpdate()->first();
                    $target = $medical;
                }

                if (!$target) {
                    return response()->json(['status' => '01', 'message' => 'Record Not Found'], 404);
                }

                if ($target->payment_status === 'paid') {
                    return response()->json(['status' => '00', 'message' => 'Already Paid']);
                }

                // Update Target Model
                if ($challan) {
                    $challan->update([
                        'payment_status' => 'paid',
                        'status' => 'paid',
                        'transaction_id' => $request->transaction_id
                    ]);
                } else {
                    $medical->update([
                        'payment_status' => 'paid'
                    ]);
                }

                // Create Payment Record
                $payment = Payment::create([
                    'medical_request_id' => $medical ? $medical->id : null,
                    'challan_id' => $challan ? $challan->id : null,
                    'psid' => $psid,
                    'amount' => $request->amount_paid,
                    'transaction_id' => $request->transaction_id,
                    'payment_method' => 'bank_transfer',
                    'status' => 'success',
                    'payment_gateway_response' => $request->all(),
                    'paid_at' => now(),
                ]);

                // Audit Log
                $this->logAction($psid, 'payment', 'U', 'P', 'Payment successful via bank API', $payment->id);

                return response()->json(['status' => '00', 'message' => 'Payment Successful']);
            });

        } catch (\Exception $e) {
            Log::error("Payment Callback Error: " . $e->getMessage());
            return response()->json(['status' => '99', 'message' => 'System Error'], 500);
        }
    }

    private function isValidToken(Request $request)
    {
        $token = $request->header('Authorization');
        if (!$token) {
            $token = $request->query('token');
        }
        
        $expectedToken = config('bank.sandbox.callback_token');
        
        if (empty($expectedToken)) return true; // Disabled security if no token configured

        return $token === 'Bearer ' . $expectedToken || $token === $expectedToken;
    }

    private function logAction(string $psid, string $action, string $oldStatus, string $newStatus, string $reason, ?int $paymentId = null): void
    {
        try {
            PaymentAuditLog::create([
                'payment_id' => $paymentId,
                'action' => $action,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $psid . ': ' . $reason,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => ['psid' => $psid]
            ]);
        } catch (\Exception $e) {
            Log::warning("Could not log payment action: " . $e->getMessage());
        }
    }
}
