<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class DesignationsSeeder extends Seeder
{
    public function run()
    {
        // List of all designations (also roles)
        $items = [
            ['name' => 'Incharge', 'code' => 'incharge'],
            ['name' => 'Duty Officer', 'code' => 'duty_officer'],
            ['name' => 'Data Entry Operator', 'code' => 'deo'],
            ['name' => 'Lifter Challan Officer', 'code' => 'lifter_challan_officer'],
            ['name' => 'Warning Officer', 'code' => 'warning_officer'],
            ['name' => 'Medical Assistant', 'code' => 'medical_assistant'],
            ['name' => 'Circle Officer', 'code' => 'circle_officer'],
            ['name' => 'Reader', 'code' => 'reader'],
            ['name' => 'Accountant', 'code' => 'accountant'],
            ['name' => 'Doctor', 'code' => 'doctor'],
            ['name' => 'Chief Traffic Officer', 'code' => 'cto'],
            ['name' => 'Deputy Inspector General', 'code' => 'dig'],
            ['name' => 'Addl Inspector General', 'code' => 'addl_ig'],
            ['name' => 'Inspector General', 'code' => 'ig'],
            ['name' => 'Citizen', 'code' => 'citizen'],
        ];

        foreach ($items as $item) {
            // 1️⃣ Create or find the Designation record
            $designation = Designation::updateOrCreate(
                ['code' => $item['code']],
                ['name' => $item['name']]
            );

            // 2️⃣ Also ensure a matching Role exists
            Role::firstOrCreate(
                ['name' => $item['code']], // roles use 'code' as slug (e.g. 'cto')
                ['guard_name' => 'web']
            );
        }

        $this->command->info('✅ Designations and Roles seeded successfully!');
    }
}
