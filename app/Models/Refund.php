<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payment_id',
        'refund_transaction_id',
        'amount',
        'status',
        'reason',
        'requested_by',
        'approved_by',
        'approved_at',
        'completed_at',
        'gateway_response',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(\App\Models\Payment::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
