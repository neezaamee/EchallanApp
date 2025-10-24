<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'name','cnic','email','department','designation','current_posting','created_by','can_create_officer'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'cnic', 'cnic');
    }

    public function roleName()
    {
        $user = $this->user()->with('roles')->first();
        if (! $user) return null;
        $role = $user->roles()->first();
        return $role ? $role->name : null;
    }
}
