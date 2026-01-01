<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        \App\Models\PaymentAuditLog::create([
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'action' => 'created',
            'new_status' => $payment->status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => ['amount' => $payment->amount, 'method' => $payment->payment_method],
        ]);
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        if ($payment->isDirty('status')) {
            \App\Models\PaymentAuditLog::create([
                'payment_id' => $payment->id,
                'user_id' => auth()->id(),
                'action' => 'updated',
                'old_status' => $payment->getOriginal('status'),
                'new_status' => $payment->status,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => ['changes' => $payment->getChanges()],
            ]);
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
