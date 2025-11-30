<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'citizen_id',
        'medical_center_id',
        'status',
        'payment_status',
        'psid',
        'amount',
        'doctor_action_by',
        'doctor_action_at',
        'created_by',
    ];

    protected $casts = [
        'doctor_action_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class);
    }

    public function doctorActionBy()
    {
        return $this->belongsTo(User::class, 'doctor_action_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
