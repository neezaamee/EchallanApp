<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffType extends Model
{
    protected $fillable = ['name','display_name'];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }
}
