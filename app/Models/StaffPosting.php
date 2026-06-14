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
        'sector_id',
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

    public function sector()
    {
        return $this->belongsTo(Sector::class);
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

    // -------------------------
    // HIERARCHY HELPERS
    // -------------------------

    public function getCircleName()
    {
        if ($this->circle_id && $this->circle) return $this->circle->name;
        if ($this->sector_id && $this->sector && $this->sector->circle) return $this->sector->circle->name;
        if ($this->dumping_point_id && $this->dumpingPoint && $this->dumpingPoint->circle) return $this->dumpingPoint->circle->name;
        if ($this->medical_center_id && $this->medicalCenter && $this->medicalCenter->circle) return $this->medicalCenter->circle->name;
        return '—';
    }

    public function getCityName()
    {
        if ($this->city_id && $this->city) return $this->city->name;
        if ($this->circle_id && $this->circle && $this->circle->city) return $this->circle->city->name;
        if ($this->sector_id && $this->sector && $this->sector->circle && $this->sector->circle->city) return $this->sector->circle->city->name;
        if ($this->dumping_point_id && $this->dumpingPoint && $this->dumpingPoint->circle && $this->dumpingPoint->circle->city) return $this->dumpingPoint->circle->city->name;
        if ($this->medical_center_id && $this->medicalCenter && $this->medicalCenter->circle && $this->medicalCenter->circle->city) return $this->medicalCenter->circle->city->name;
        return '—';
    }

    public function getProvinceName()
    {
        if ($this->province_id && $this->province) return $this->province->name;
        if ($this->city_id && $this->city && $this->city->province) return $this->city->province->name;
        if ($this->circle_id && $this->circle && $this->circle->city && $this->circle->city->province) return $this->circle->city->province->name;
        if ($this->sector_id && $this->sector && $this->sector->circle && $this->sector->circle->city && $this->sector->circle->city->province) return $this->sector->circle->city->province->name;
        if ($this->dumping_point_id && $this->dumpingPoint && $this->dumpingPoint->circle && $this->dumpingPoint->circle->city && $this->dumpingPoint->circle->city->province) return $this->dumpingPoint->circle->city->province->name;
        if ($this->medical_center_id && $this->medicalCenter && $this->medicalCenter->circle && $this->medicalCenter->circle->city && $this->medicalCenter->circle->city->province) return $this->medicalCenter->circle->city->province->name;
        return '—';
    }
}
