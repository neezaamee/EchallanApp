<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_id',
        'name',
        'slug',
    ];

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }

    public function postings()
    {
        return $this->hasMany(StaffPosting::class);
    }
}
