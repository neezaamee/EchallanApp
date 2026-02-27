<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Challan;
use App\Models\MedicalRequest;
use Illuminate\Support\Str;

class PaymentIntegrationController extends Controller
{
    /**
     * Bank Inquiry API
     * The bank calls this to fetch consumer info before initializing payment.
     * POST /api/payment/inquiry
     */
    public function inquiry(Request $request)
    {
        // 1. Validate Consumer Number (PSID)
        // Bank might send it as 'consumer_number' or 'psid'
        $request->validate([
            'consumer_number' => 'required|string|size:20'
        ]);

        $psid = $request->consumer_number;

        // 2. Find Record
        $challan = Challan::where('psid', $psid)->first();
        if ($challan) {
            return response()->json([
                'status' => '00', // Success code
                'message' => 'Record Found',
                'data' => [
                    'consumer_number' => $psid,
                    'consumer_name' => $challan->violator_name,
                    'amount_due' => $challan->fine_amount,
                    'amount_within_due_date' => $challan->fine_amount,
                    'amount_after_due_date' => $challan->fine_amount, // If different logic exists, apply here
                    'billing_month' => $challan->created_at->format('Ym'),
                    'due_date' => $challan->created_at->addDays(30)->format('Ymd'),
                    'status' => $challan->payment_status === 'paid' ? 'P' : 'U' // P=Paid, U=Unpaid
                ]
            ]);
        }

        $medical = MedicalRequest::where('psid', $psid)->first();
        if ($medical) {
             // Calculate amount logic if not in DB? Assuming standard fee or field exists
             // For demo, standard fee 500
             $amount = 500; // Modify as per actual logic

            return response()->json([
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
            ]);
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
        // Validate payload
        $request->validate([
            'consumer_number' => 'required|string|size:20',
            'transaction_id' => 'required|string',
            'amount_paid' => 'required|numeric',
            'transaction_date' => 'sometimes|string'
            // 'auth_token' => 'required' // In real world, check header token
        ]);

        $psid = $request->consumer_number;
        
        $challan = Challan::where('psid', $psid)->first();
        if ($challan) {
            if ($challan->payment_status === 'paid') {
                return response()->json(['status' => '00', 'message' => 'Already Paid']);
            }

            $challan->update([
                'payment_status' => 'paid',
                'status' => 'paid',
                'transaction_id' => $request->transaction_id
            ]);

            return response()->json(['status' => '00', 'message' => 'Payment Successful']);
        }

        $medical = MedicalRequest::where('psid', $psid)->first();
        if ($medical) {
            if ($medical->payment_status === 'paid') {
                return response()->json(['status' => '00', 'message' => 'Already Paid']);
            }
            
            $medical->update([
                'payment_status' => 'paid',
                // 'status' => 'paid' // Medical might have other workflow status
            ]);

            return response()->json(['status' => '00', 'message' => 'Payment Successful']);
        }

        return response()->json(['status' => '01', 'message' => 'Record Not Found'], 404);
    }
}
