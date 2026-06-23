<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffSeeder extends Seeder
{
    private function resolveRank($role, $rules)
    {
        $rule = $rules[$role] ?? ['random'];

        // Fixed rank
        if (isset($rule['fixed'])) {
            return $rule['fixed'];
        }

        // Range rank
        if (isset($rule['range']) && is_array($rule['range'])) {
            return rand($rule['range'][0], $rule['range'][1]);
        }

        // Default random
        return rand(1, 7);
    }
    public function run(): void
    {
        // Role => Number of Users Required
        $roles = [
            'incharge'               => 1,
            'duty_officer'           => 1,
            'deo'                    => 1,
            'lifter_challan_officer' => 3,
            'warning_officer'        => 3,
            'medical_assistant'      => 2,
            'circle_officer'         => 7,
            'reader'                 => 1,
            'accountant'             => 1,
            'doctor'                 => 3,
            'cto'                    => 1,
            'dig'                    => 1,
            'addl_ig'                => 1,
            'ig'                     => 1,
        ];

        $rankRules = [
            'doctor'                 => ['fixed' => 1],
            'medical_assistant'      => ['fixed' => 2],
            'deo'                    => ['fixed' => 2],
            'circle_officer'         => ['fixed' => 6],
            'cto'                    => ['fixed' => 7],
            'dig'                    => ['fixed' => 8],
            'addl_ig'                => ['fixed' => 9],
            'ig'                     => ['fixed' => 10],
            'accountant'             => ['range' => [2, 4]],
            'reader'                 => ['range' => [5, 6]],
            'lifter_challan_officer' => ['range' => [5, 6]],
            'warning_officer'        => ['range' => [5, 6]],
            'incharge'               => ['range' => [5, 6]],
        ];

        foreach ($roles as $role => $count) {
            for ($i = 1; $i <= $count; $i++) {

                // Create user
                $user = User::firstOrCreate(
                    ['email' => $role . $i . '@example.com'],
                    [
                        'name' => ucfirst(str_replace('_', ' ', $role)) . " $i",
                        'password' => Hash::make('password'),
                        'cnic' => fake()->unique()->numerify('3############'),
                        'email_verified_at' => now(),
                        'is_department_user' => true,
                    ]
                );

                // Assign Spatie role
                if (!$user->hasRole($role)) {
                    $user->assignRole($role);
                }

                // Resolve Spatie role record ID
                $spatieRole = Role::where('name', $role)->first();
                $roleId = $spatieRole ? $spatieRole->id : null;

                // Create staff record
                Staff::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'first_name' => ucfirst($role),
                        'last_name' => "User $i",
                        'belt_no' => fake()->numerify('B####'),
                        'phone' => fake()->phoneNumber(),
                        'email' => $user->email,
                        'cnic' => $user->cnic,
                        'gender' => 'male',
                        'role_id' => $roleId,
                        // 🎉 Role-wise clean rank logic
                        'rank_id' => $this->resolveRank($role, $rankRules),
                        'status' => 'active',
                        'photo' => null,
                        'created_by' => 1,
                    ]
                );
            }
        }
    }
}
