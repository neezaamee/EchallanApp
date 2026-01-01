<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class City extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
    /**
     * The table associated with the model.
     * Change this to match your database table
     */
    protected $table = 'cities';

    /**
     * The attributes that are mass assignable.
     * Add or remove fields as needed for your table
     */
    protected $fillable = ['province_id', 'name', 'slug', 'is_active'];

    /**
     * The attributes that should be cast.
     * Adjust casts based on your field types
     */
    protected $casts = [
        /* 'province_id' => 'integer',
        'population' => 'integer',
        'area' => 'decimal:2', */
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'deleted_at',
    ];
    /**
     * Get the province that owns the city.
     * This is an example of a belongsTo relationship
     * Change this method name and relationship to match your needs
    */

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function circles()
    {
        return $this->hasMany(Circle::class);
    }

    /**
     * Scope to filter active records
     * You can add more scopes as needed
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
    }

    /**
     * Scope to filter by province
     */
    public function scopeByProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

}
