<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // All designations and roles mapping (as per your list)
        $designations = [
            'incharge'          => 1,
            'duty_officer'      => 2,
            'deo'               => 3,
            'circle_officer'    => 4,
            'cto'               => 5,
            'reader'            => 6,
            'accountant'        => 7,
            'doctor'            => 8,
            'challan_officer'   => 9,
            'citizen'           => 10,
        ];

        foreach ($designations as $role => $designationId) {

            // Create user
            $user = User::create([
                'name' => ucfirst(str_replace('_', ' ', $role)),
                'email' => $role . '@example.com',
                'password' => Hash::make('password'),
                'cnic' => fake()->unique()->numerify('3############'),
                'email_verified_at' => now()->subYears(25),
                'is_department_user' => 1,
            ]);

            // Assign Spatie role
            $user->assignRole($role);

            // Create staff record (citizens usually do NOT become staff)
            if ($role !== 'citizen') {
                Staff::create([
                    'user_id' => $user->id,
                    'first_name' => ucfirst($role),
                    'last_name' => 'User',
                    'belt_no' => fake()->numerify('B####'),
                    'phone' => fake()->phoneNumber(),
                    'email' => $user->email,
                    'cnic' => $user->cnic,
                    'gender' => 'male',
                    'date_of_birth' => now()->subYears(25),
                    'designation_id' => $designationId,
                    'rank_id' => 1, // set default rank
                    'status' => 'active',
                    'photo' => null,
                    'created_by' => 1, // super admin ID
                ]);
            }
        }
    }
}
