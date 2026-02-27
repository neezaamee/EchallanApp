<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickUpPoint extends Model
{
    protected $fillable = [
        'dumping_point_id',
        'name',
        'location',
        'is_active',
    ];

    public function dumpingPoint()
    {
        return $this->belongsTo(DumpingPoint::class);
    }
}
