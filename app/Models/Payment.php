<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\HasJurisdiction;

class Payment extends Model
{
    use HasFactory, SoftDeletes, HasJurisdiction;

    protected $fillable = [
        'medical_request_id',
        'challan_id',
        'psid',
        'amount',
        'transaction_id',
        'payment_method',
        'status',
        'payment_gateway_response',
        'paid_at',
        'receipt_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($payment) {
            if ($payment->status === 'success' && empty($payment->receipt_number)) {
                $datePart = $payment->paid_at ? $payment->paid_at->format('Ymd') : now()->format('Ymd');
                $payment->receipt_number = 'RCP-' . $datePart . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);
                $payment->saveQuietly();
            }
        });
        
        static::updated(function ($payment) {
            if ($payment->status === 'success' && empty($payment->receipt_number)) {
                $datePart = $payment->paid_at ? $payment->paid_at->format('Ymd') : now()->format('Ymd');
                $payment->receipt_number = 'RCP-' . $datePart . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);
                $payment->saveQuietly();
            }
        });
    }

    /**
     * Get the medical request associated with this payment
     */
    public function medicalRequest()
    {
        return $this->belongsTo(MedicalRequest::class);
    }

    /**
     * Get the challan associated with this payment
     */
    public function challan()
    {
        return $this->belongsTo(Challan::class);
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

    /**
     * Scope to filter payments based on search criteria
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['keyword'] ?? null, function ($q, $keyword) {
            $q->where(function ($subQ) use ($keyword) {
                $subQ->where('psid', 'like', "%{$keyword}%")
                    ->orWhere('transaction_id', 'like', "%{$keyword}%");
            });
        })->when($filters['cnic'] ?? null, function ($q, $cnic) {
            $q->whereHas('medicalRequest.citizen', function ($subQ) use ($cnic) {
                $subQ->where('cnic', 'like', "%{$cnic}%");
            });
        })->when($filters['date_from'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', '>=', $date);
        })->when($filters['date_to'] ?? null, function ($q, $date) {
            $q->whereDate('created_at', '<=', $date);
        })->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        })->when($filters['payment_method'] ?? null, function ($q, $method) {
            $q->where('payment_method', $method);
        })->when($filters['medical_center_id'] ?? null, function ($q, $centerId) {
            $q->whereHas('medicalRequest', function ($subQ) use ($centerId) {
                $subQ->where('medical_center_id', $centerId);
            });
        });
    }
}
