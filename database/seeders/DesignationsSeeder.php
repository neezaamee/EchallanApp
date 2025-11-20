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
            ['name' => 'Challan Officer', 'code' => 'challan_officer'],
            ['name' => 'Circle Officer', 'code' => 'circle_officer'],
            ['name' => 'Reader', 'code' => 'reader'],
            ['name' => 'Accountant', 'code' => 'accountant'],
            ['name' => 'Doctor', 'code' => 'doctor'],
            ['name' => 'Chief Traffic Officer', 'code' => 'cto'],
            ['name' => 'Deputy Inspector General', 'code' => 'dig'],
            ['name' => 'Inspector General', 'code' => 'ig'],
            ['name' => 'Addl Inspector General', 'code' => 'addlig'],
            ['name' => 'Citizen', 'code' => 'citizen'],
        ];

        foreach ($items as $item) {
            // 1️⃣ Create or find the Designation record
            $designation = Designation::firstOrCreate(
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
