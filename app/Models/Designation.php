<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['name', 'code'];
    protected static function booted()
    {
        static::created(function ($designation) {
            \Spatie\Permission\Models\Role::firstOrCreate(
                ['name' => $designation->code],
                ['guard_name' => 'web']
            );
        });
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
