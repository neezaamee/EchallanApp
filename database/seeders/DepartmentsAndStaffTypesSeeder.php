<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\StaffType;

class DepartmentsAndStaffTypesSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Traffic', 'short_code' => 'TRF'],
            ['name' => 'Finance', 'short_code' => 'FIN'],
            ['name' => 'Health', 'short_code' => 'HLT'],
        ];

        foreach ($departments as $d) {
            Department::firstOrCreate(['name' => $d['name']], $d);
        }

        $types = [
            ['name' => 'doctor', 'display_name' => 'Doctor'],
            ['name' => 'challan_officer', 'display_name' => 'Challan Officer'],
            ['name' => 'clerk', 'display_name' => 'Clerk/Staff'],
        ];

        foreach ($types as $t) {
            StaffType::firstOrCreate(['name' => $t['name']], $t);
        }
    }
}
