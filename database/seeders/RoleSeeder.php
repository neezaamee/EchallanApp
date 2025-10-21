<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'super_admin', 'display_name' => 'Super Admin'],
            ['name' => 'admin', 'display_name' => 'Admin'],
            ['name' => 'challan_officer', 'display_name' => 'Challan Officer'],
            ['name' => 'accountant', 'display_name' => 'Accountant'],
            ['name' => 'violator', 'display_name' => 'Violator'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
