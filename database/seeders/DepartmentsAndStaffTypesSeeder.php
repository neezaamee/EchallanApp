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
            ['name' => 'medical_assistant', 'display_name' => 'Medical Assistant'],
            ['name' => 'lifter_challan_officer', 'display_name' => 'Lifter Challan Officer'],
            ['name' => 'warning_officer', 'display_name' => 'Warning Officer'],
            ['name' => 'clerk', 'display_name' => 'Clerk/Staff'],
        ];

        foreach ($types as $t) {
            StaffType::updateOrCreate(['name' => $t['name']], $t);
        }
    }
}
