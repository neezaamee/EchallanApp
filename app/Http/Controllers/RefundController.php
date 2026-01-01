<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = \App\Models\Refund::with('payment.medicalRequest.citizen', 'requestedBy', 'approvedBy')
            ->latest()
            ->paginate(20);

        return view('pages.refunds.index', compact('refunds'));
    }

    public function create(\App\Models\Payment $payment)
    {
        // Check if payment is eligible for refund
        if (!$payment->isSuccess()) {
            return redirect()->back()->with('error', 'Only successful payments can be refunded.');
        }

        // Check if refund already exists
        if ($payment->refunds()->whereIn('status', ['pending', 'approved', 'completed'])->exists()) {
            return redirect()->back()->with('error', 'A refund request already exists for this payment.');
        }

        return view('pages.refunds.create', compact('payment'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:1000',
        ]);

        $payment = \App\Models\Payment::findOrFail($request->payment_id);

        // Validate amount doesn't exceed payment amount
        if ($request->amount > $payment->amount) {
            return redirect()->back()->with('error', 'Refund amount cannot exceed payment amount.');
        }

        // Generate unique refund transaction ID
        $refundTxnId = 'RFD' . strtoupper(\Illuminate\Support\Str::random(12));

        $refund = \App\Models\Refund::create([
            'payment_id' => $payment->id,
            'refund_transaction_id' => $refundTxnId,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'requested_by' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('refunds.index')->with('success', 'Refund request submitted successfully.');
    }

    public function show(\App\Models\Refund $refund)
    {
        $refund->load('payment.medicalRequest.citizen', 'requestedBy', 'approvedBy');
        return view('pages.refunds.show', compact('refund'));
    }

    public function approve(\App\Models\Refund $refund)
    {
        if ($refund->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending refunds can be approved.');
        }

        $refund->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // In a real system, you would process the refund with the payment gateway here
        // For now, we'll mark it as completed immediately
        $refund->update([
            'status' => 'completed',
            'completed_at' => now(),
            'gateway_response' => [
                'gateway' => 'Dummy Refund Gateway',
                'response_code' => '00',
                'response_message' => 'Refund Processed Successfully',
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        return redirect()->route('refunds.show', $refund)->with('success', 'Refund approved and processed successfully.');
    }

    public function reject(\App\Models\Refund $refund, \Illuminate\Http\Request $request)
    {
        if ($refund->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending refunds can be rejected.');
        }

        $refund->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('refunds.show', $refund)->with('success', 'Refund request rejected.');
    }
}
