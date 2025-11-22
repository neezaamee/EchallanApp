<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'province_id',
        'city_id',
        'circle_id',
        'dumping_point_id',
        'medical_center_id',
        'start_date',
        'end_date',
        'status',
    ];

    // -------------------------
    // RELATIONSHIPS
    // -------------------------

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }

    public function dumpingPoint()
    {
        return $this->belongsTo(DumpingPoint::class);
    }

    // optional (only if you later add medical_center_id)
    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class);
    }
    public function postings()
    {
        return $this->hasMany(StaffPosting::class);
    }

    public function activePosting()
    {
        return $this->hasOne(StaffPosting::class)->where('status', 'active');
    }
}
