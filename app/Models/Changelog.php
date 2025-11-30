<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    protected $fillable = [
        'version',
        'release_date',
        'type',
        'title',
        'description',
        'is_published',
        'order',
    ];

    protected $casts = [
        'release_date' => 'date',
        'is_published' => 'boolean',
        'order' => 'integer',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByVersion($query, $version)
    {
        return $query->where('version', $version);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('release_date', 'desc')
                     ->orderBy('order', 'asc');
    }

    // Helper method to get type badge color
    public function getTypeBadgeColorAttribute()
    {
        return match($this->type) {
            'added' => 'success',
            'changed' => 'info',
            'fixed' => 'warning',
            'removed' => 'danger',
            'security' => 'danger',
            'deprecated' => 'secondary',
            default => 'secondary',
        };
    }
}
