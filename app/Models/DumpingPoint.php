<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DumpingPoint extends Model
{
    use HasFactory;

    // ✅ Fields that can be mass-assigned
    protected $fillable = [
        'name',
        'location',
        'circle_id',
    ];

    /**
     * Each dumping point belongs to one circle.
     */
    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }

}
