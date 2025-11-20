<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Circle extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'slug',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function dumpingPoints()
    {
        return $this->hasMany(DumpingPoint::class);
    }
}
