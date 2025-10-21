<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $email = 'super-admin@ctpfsd.gop.pk';
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'cnic' => '0000000000000', // replace if you want; must be unique
                'password' => Hash::make('ctpfaisalabad'),
                'email_verified_at' => now(),
                'is_department_user' => true,
            ]
        );

        $role = Role::firstOrCreate(['name' => 'super_admin'], ['display_name' => 'Super Admin']);

        if (! $user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->attach($role->id);
        }
    }
}
