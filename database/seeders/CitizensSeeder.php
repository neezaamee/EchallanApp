<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Citizen;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CitizensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // All designations and roles mapping (as per your list)
        $roles = [
            'citizen' => 4,
        ];

        foreach ($roles as $role => $roleId) {

            // Create user
            $user = User::create([
                'name' => ucfirst(fake()->name()),
                'email' => fake()->unique()->email(),
                'password' => Hash::make('password'),
                'cnic' => fake()->unique()->numerify('3############'),
                'email_verified_at' => now()->subDays(2),
                'is_department_user' => false,
            ]);

            // Assign Spatie role
            $user->assignRole($role);

            // Create citizens
            if ($role == 'citizen') {
                Citizen::create([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'father_name' => ucfirst(fake()->name()),
                    'gender' => 'male',
                    'cnic' => $user->cnic,
                    'email' => $user->email,
                    'phone' => fake()->phoneNumber(),
                    'role_id' => $roleId,
                    'status' => 'active',
                ]);
            }
        }
    }
}
