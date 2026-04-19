<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalRequest;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Challan;
use App\Models\PaymentAuditLog;
use Illuminate\Support\Facades\Log;

class OneLinkController extends Controller
{
    /**
     * Bill Inquiry API (Simulation)
     */
    public function inquiry(Request $request)
    {
        $request->validate([
            'consumer_number' => 'required|string', // PSID
        ]);

        $psid = $request->consumer_number;

        // Try Challan first
        $target = Challan::where('psid', $psid)->first();
        $name = null;
        $amount = 0;

        if ($target) {
            $name = $target->violator_name;
            $amount = $target->fine_amount;
        } else {
            $target = MedicalRequest::where('psid', $psid)->first();
            if ($target) {
                $name = $target->citizen->full_name ?? 'Citizen';
                $amount = $target->amount;
            }
        }

        if (!$target) {
            return response()->json([
                'response_code' => '01',
                'response_message' => 'Consumer not found',
            ], 404);
        }

        $isPaid = $target->isPaid();
        $status = $isPaid ? 'P' : 'U';

        $category = 'Others';
        if ($target instanceof Challan) {
             $category = (str_contains(strtolower($target->vehicle_type), 'bike') || str_contains(strtolower($target->vehicle_type), 'motorcycle')) ? 'Bike' : (str_contains(strtolower($target->vehicle_type), 'car') ? 'Car' : 'Traffic');
        } elseif ($target instanceof MedicalRequest) {
            $category = 'Medical';
        }

        $response = [
            'response_code' => '00',
            'bill_status' => $status,
            'amount_within_due_date' => $isPaid ? '0' : (string) $amount,
            'amount_after_due_date' => $isPaid ? '0' : (string) $amount,
            'created_at' => $target->created_at->format('Ymd'),
            'due_date' => $target->created_at->addDays(30)->format('Ymd'),
            'consumer_name' => $name ?? 'Unknown',
            'category' => $category
        ];

        $this->logAction($psid, 'inquiry_1link', $status, $status, '1Link Inquiry');

        return response()->json($response);
    }

    /**
     * Bill Payment API (Simulation)
     */
    public function payment(Request $request)
    {
        $request->validate([
            'consumer_number' => 'required|string',
            'amount_paid' => 'required|numeric',
            'transaction_id' => 'required|string',
            'transaction_datetime' => 'nullable|string',
        ]);

        $psid = $request->consumer_number;
        $amountPaid = $request->amount_paid;

        // Find Target
        $challan = Challan::where('psid', $psid)->first();
        $medical = null;
        $target = $challan;

        if (!$challan) {
            $medical = MedicalRequest::where('psid', $psid)->first();
            $target = $medical;
        }

        if (!$target) {
            return response()->json([
                'response_code' => '01',
                'response_message' => 'Consumer not found',
            ], 404);
        }

        if ($target->isPaid()) {
            return response()->json([
                'response_code' => '02',
                'response_message' => 'Bill already paid',
            ], 400);
        }

        $targetAmount = $challan ? $challan->fine_amount : $medical->amount;

        if ($amountPaid < $targetAmount) {
             return response()->json([
                'response_code' => '03',
                'response_message' => 'Insufficient Amount',
            ], 400);
        }

        try {
            DB::beginTransaction();

            $transactionId = $request->transaction_id;
            
            // Create Payment Record
            $payment = Payment::create([
                'medical_request_id' => $medical ? $medical->id : null,
                'challan_id' => $challan ? $challan->id : null,
                'psid' => $psid,
                'amount' => $amountPaid,
                'transaction_id' => $transactionId,
                'payment_method' => 'mobile_wallet', // Simulation assumption
                'status' => 'success',
                'payment_gateway_response' => $request->all(),
                'paid_at' => now(),
            ]);

            // Update Entity
            if ($challan) {
                $challan->update([
                    'payment_status' => 'paid',
                    'status' => 'paid',
                    'transaction_id' => $transactionId
                ]);
            } else {
                $medical->update([
                    'payment_status' => 'paid'
                ]);
            }

            $this->logAction($psid, 'payment_1link', 'U', 'P', '1Link Payment Successful', $payment->id);

            DB::commit();

            return response()->json([
                'response_code' => '00',
                'response_message' => 'Payment Successful',
                'identification_parameter' => $transactionId,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("1Link Payment Error: " . $e->getMessage());
            return response()->json([
                'response_code' => '99',
                'response_message' => 'System Error',
            ], 500);
        }
    }

    private function logAction($psid, $action, $oldStatus, $newStatus, $reason, $paymentId = null)
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
            Log::warning("Could not log 1Link action: " . $e->getMessage());
        }
    }
}
