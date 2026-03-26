<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasJurisdiction;

class Province extends Model
{
    use HasJurisdiction;
    protected $fillable = ['name','code'];
    public function cities() { return $this->hasMany(City::class); }
}
