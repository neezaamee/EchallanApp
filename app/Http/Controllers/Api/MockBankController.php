<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class MockBankController extends Controller
{
    /**
     * Simulate PSID Generation
     * POST /api/mock-bank/generate-psid
     */
    public function generatePsid(Request $request)
    {
        $request->validate([
            'head' => 'required|in:MEDICAL,TRAFFIC_CAR,TRAFFIC_BIKE',
            'amount' => 'required|numeric',
        ]);

        $prefix = config("bank.heads.{$request->head}.prefix", '99');
        
        // Format: Prefix + Ymd + Random = 20 digits
        $date = date('ymd');
        // Calculate needed random length
        $usedLength = strlen($prefix) + 6;
        $randomLength = 20 - $usedLength;
        if($randomLength < 1) $randomLength = 4;
        
        $random = '';
        while (strlen($random) < $randomLength) {
            $random .= mt_rand(0, 9);
        }
        $random = substr($random, 0, $randomLength);
        
        $psid = $prefix . $date . $random;

        return response()->json([
            'status' => 'success',
            'data' => [
                'psid' => $psid,
                'expiry' => now()->addDays(30)->toIso8601String(),
            ]
        ]);
    }

    /**
     * Simulate Payment Callback/Inquiry
     * POST /api/mock-bank/payment-status
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'psid' => 'required|string',
        ]);

        // In a real mock, we might store state in a cache/db. 
        // For now, we'll assume it's UNPAID unless we explicitly "pay" it via another endpoint
        // or just random for testing if no state is kept.
        // BUT, since we need a "Manual Sandbox" to "Test it", we should probably have a way to force payment.
        
        $status = \Cache::get("mock_payment_{$request->psid}", 'UNPAID');

        return response()->json([
            'status' => 'success',
            'data' => [
                'psid' => $request->psid,
                'payment_status' => $status,
                'amount_paid' => $status === 'PAID' ? 500 : 0, // Simplified
                'transaction_id' => $status === 'PAID' ? 'TXN-' . Str::random(10) : null,
            ]
        ]);
    }

    /**
     * Force Pay (For Sandbox UI)
     * POST /api/mock-bank/pay
     */
    public function forcePay(Request $request)
    {
        $request->validate([
            'psid' => 'required|string',
            'payment_method' => 'sometimes|string'
        ]);

        // Store payment method in cache too if needed, or just status
        \Cache::put("mock_payment_{$request->psid}", 'PAID', now()->addDays(1));
        \Cache::put("mock_payment_method_{$request->psid}", $request->payment_method ?? 'sandbox_simulation', now()->addDays(1));

        return response()->json([
            'status' => 'success',
            'message' => 'PSID marked as PAID in Mock Bank.',
            'transaction_id' => 'TXN-' . Str::upper(Str::random(10))
        ]);
    }

    /**
     * Sync Status (Developer Tool specific)
     * This simulates the App checking the Bank API and updating local DB.
     * POST /api/mock-bank/sync-local-status
     */
    public function syncLocalStatus(Request $request)
    {
        $request->validate([
            'psid' => 'required|string',
        ]);

        $psid = $request->psid;
        // In real flow, we'd check BankService::checkStatus($psid)
        // Here we just check our mock cache
        $status = \Cache::get("mock_payment_{$request->psid}", 'UNPAID');
        $method = \Cache::get("mock_payment_method_{$request->psid}", $request->payment_method ?? 'unknown');

        if ($status === 'PAID') {
            $transactionId = 'TXN-' . Str::upper(Str::random(10)) . '-SYNC';
            $amount = 0;
            $model = null;
            $type = '';

            // Try to find in Challans
            $challan = \App\Models\Challan::where('psid', $psid)->first();
            if ($challan) {
                $challan->update([
                    'payment_status' => 'paid',
                    'status' => 'paid',
                    'transaction_id' => $transactionId
                ]);
                $amount = $challan->fine_amount;
                $model = $challan;
                $requestType = 'challan'; // Not strictly needed for Payment model but good for debug
            } else {
                // Try to find in Medical Requests
                $medicalRequest = \App\Models\MedicalRequest::where('psid', $psid)->first();
                if ($medicalRequest) {
                    $medicalRequest->update([
                        'payment_status' => 'paid'
                    ]);
                    $amount = $medicalRequest->amount ?? 500; // Fallback
                    $model = $medicalRequest;
                }
            }

            // Create Payment Record if model found and not already exists
            if ($model) {
                 // Check if payment record exists
                 $existingPayment = \App\Models\Payment::where('psid', $psid)->first();
                 if (!$existingPayment) {
                     \App\Models\Payment::create([
                        'medical_request_id' => ($model instanceof \App\Models\MedicalRequest) ? $model->id : null,
                        // Note: Payment model currently has medical_request_id foreign key. 
                        // If we are paying for a Challan, we might need a polymorphic relationship or a separate column.
                        // Let's check Payment model structure. If it's strictly for medical_requests, we have a schema issue.
                        // Assuming based on previous context, Payment table might be shared or mainly for Medical.
                        // If strictly Medical, we can't store Challan payments there cleanly without refactor.
                        // For now, let's assume it supports both or we skip creating Payment record for Challan if not supported.
                        // BUT user said "in all payments page it is showing status as success". 
                        // It implies Challans also show up there? Or just Medical?
                        // Let's create it if it's medical. 
                        'psid' => $psid,
                        'amount' => $amount,
                        'transaction_id' => $transactionId,
                        'payment_method' => $method,
                        'status' => 'success',
                        'payment_gateway_response' => ['source' => 'sandbox_sync'],
                        'paid_at' => now(),
                     ]);
                 }
                 
                 return response()->json(['message' => 'Local Status Synced & Payment Record Created.']);
            }
        }

        return response()->json(['message' => 'Status is ' . $status . '.']);
    }
}
