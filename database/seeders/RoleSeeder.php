<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'super_admin', 'guard_name' => 'Super Admin'],
            ['name' => 'admin', 'guard_name' => 'Admin'],
            ['name' => 'challan_officer', 'guard_name' => 'Challan Officer'],
            ['name' => 'accountant', 'guard_name' => 'Accountant'],
            ['name' => 'violator', 'guard_name' => 'Violator'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
