<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Staff extends Model
{
    use HasRoles, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
    protected $guard_name = 'web';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'belt_no',
        'phone',
        'email',
        'cnic',
        'gender',
        'status',
        'rank_id',
        'created_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnlinked($query)
    {
        return $query->whereNull('user_id');
    }


    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fullName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
    public function activePosting()
    {
        return $this->hasOne(StaffPosting::class)->where('status', 'active');
    }
    public function activeDoctorPosting()
    {
        return $this->hasOne(StaffPosting::class)
            ->where('status', 'active')
            ->whereNotNull('medical_center_id');
    }
}
