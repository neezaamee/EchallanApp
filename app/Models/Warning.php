<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warning extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'officer_id',
        'violator_name',
        'violator_cnic',
        'violator_mobile',
        'vehicle_type',
        'vehicle_number',
        'violation_name',
        'location',
        'remarks',
    ];

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
