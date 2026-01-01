<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAuditLog extends Model
{
    protected $fillable = [
        'payment_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'reason',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

