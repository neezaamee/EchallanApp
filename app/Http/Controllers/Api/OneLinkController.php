<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalRequest;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OneLinkController extends Controller
{
    /**
     * Bill Inquiry API (Simulation)
     * 
     * 1Link usually sends:
     * - Consumer Number (PSID)
     * - Bank Mnemonic
     * - Reserved fields
     * 
     * We return:
     * - Response Code (00 for specific success)
     * - Bill Status (U=Unpaid, P=Paid)
     * - Amount
     * - Consumer Detail
     */
    public function inquiry(Request $request)
    {
        // Validation mimicking 1Link requirements (loosely)
        $request->validate([
            'consumer_number' => 'required|string', // PSID
        ]);

        $psid = $request->consumer_number; // Access directly as property

        $medicalRequest = MedicalRequest::where('psid', $psid)->first();

        if (!$medicalRequest) {
            return response()->json([
                'response_code' => '01', // Invalid Consumer Number
                'response_message' => 'Consumer not found',
            ], 404);
        }

        if ($medicalRequest->isPaid()) {
             return response()->json([
                'response_code' => '00',
                'bill_status' => 'P', // Paid
                'amount_within_due_date' => '0',
                'amount_after_due_date' => '0',
                'created_at' => $medicalRequest->created_at->format('Ymd'),
                'due_date' => $medicalRequest->created_at->addDays(30)->format('Ymd'),
                'consumer_name' => $medicalRequest->citizen->full_name ?? 'Unknown',
            ]);
        }

        return response()->json([
            'response_code' => '00', // Success
            'bill_status' => 'U', // Unpaid
            'amount_within_due_date' => (string) $medicalRequest->amount,
            // Late fee logic could go here
            'amount_after_due_date' => (string) ($medicalRequest->amount), 
            'created_at' => $medicalRequest->created_at->format('Ymd'),
            'due_date' => $medicalRequest->created_at->addDays(30)->format('Ymd'),
            'consumer_name' => $medicalRequest->citizen->full_name ?? 'Unknown',
        ]);
    }

    /**
     * Bill Payment API (Simulation)
     * 
     * 1Link sends:
     * - Consumer Number
     * - Amount Paid
     * - Transaction ID (STAN/RRN)
     * - Transaction Date/Time
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

        $medicalRequest = MedicalRequest::where('psid', $psid)->first();

        if (!$medicalRequest) {
            return response()->json([
                'response_code' => '01',
                'response_message' => 'Consumer not found',
            ], 404);
        }

        if ($medicalRequest->isPaid()) {
            return response()->json([
                'response_code' => '02', // Already Paid
                'response_message' => 'Bill already paid',
            ], 400); // Or 200 with error code depending on 1Link spec
        }

        if ($amountPaid < $medicalRequest->amount) {
             return response()->json([
                'response_code' => '03', // Partial payment not allowed
                'response_message' => 'Insufficient Amount',
            ], 400);
        }

        // Process Payment
        try {
            DB::beginTransaction();

            $transactionId = $request->transaction_id;
            
            // Create Payment Record
            $payment = Payment::create([
                'medical_request_id' => $medicalRequest->id,
                'psid' => $medicalRequest->psid,
                'amount' => $amountPaid,
                'transaction_id' => $transactionId,
                'payment_method' => '1link', // Mark as 1Link
                'status' => 'success',
                'payment_gateway_response' => $request->all(),
                'paid_at' => now(),
            ]);

            // Update Medical Request
            $medicalRequest->payment_status = 'paid';
            $medicalRequest->save();

            DB::commit();

            return response()->json([
                'response_code' => '00',
                'response_message' => 'Payment Successful',
                'identification_parameter' => $transactionId, // Echo back or internal ID
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'response_code' => '99',
                'response_message' => 'System Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
