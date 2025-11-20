<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'user_id',
        'first_name','last_name','belt_no','phone','email','cnic',
        'designation_id','rank_id','city_id','province_id','created_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return trim($this->first_name.' '.$this->last_name);
    }
}
