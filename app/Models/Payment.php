<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'medical_request_id',
        'psid',
        'amount',
        'transaction_id',
        'payment_method',
        'status',
        'payment_gateway_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the medical request associated with this payment
     */
    public function medicalRequest()
    {
        return $this->belongsTo(MedicalRequest::class);
    }

    /**
     * Get the refunds associated with this payment
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * Scope to filter successful payments
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope to filter failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope to filter pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if payment is successful
     */
    public function isSuccess()
    {
        return $this->status === 'success';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'PKR ' . number_format((float) $this->amount, 2);
    }
}
