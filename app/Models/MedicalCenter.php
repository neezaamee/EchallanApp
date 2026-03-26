<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Traits\HasJurisdiction;

class MedicalCenter extends Model
{
    use HasFactory, LogsActivity, HasJurisdiction;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    protected $fillable = ['circle_id', 'name', 'location'];

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }
}
