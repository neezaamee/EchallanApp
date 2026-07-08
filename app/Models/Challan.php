<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'officer_id',
        'dumping_point_id',
        'pick_up_point_id',
        'violator_name',
        'violator_cnic',
        'violator_mobile',
        'vehicle_type',
        'vehicle_number',
        'violation_name',
        'fine_amount',
        'status',
        'payment_status',
        'transaction_id',
        'psid',
        'released_at',
        'released_by_staff_id',
        'receiver_name',
        'receiver_cnic',
        'receiver_father_name',
    ];

    protected $casts = [
        'released_at' => 'datetime',
        'fine_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        // Boot logic removed in favor of Service-based generation
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function releaseOfficer()
    {
        return $this->belongsTo(Staff::class, 'released_by_staff_id');
    }

    public function dumpingPoint()
    {
        return $this->belongsTo(DumpingPoint::class);
    }

    public function pickUpPoint()
    {
        return $this->belongsTo(PickUpPoint::class);
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isUnpaid()
    {
        return $this->payment_status === 'unpaid';
    }

    public function scopeBounded($query)
    {
        return $query->whereNull('released_at');
    }
}
