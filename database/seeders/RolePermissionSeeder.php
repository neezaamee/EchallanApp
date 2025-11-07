<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1️⃣ Define roles
        $roles = [
            'super_admin',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2️⃣ Define permissions
        $permissions = [
            'manage users',
            'manage circles',
            'manage dumping points',
            'manage staff',
            'view reports',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName]);
        }

        // 3️⃣ Assign all permissions to Super Admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $superAdminRole->syncPermissions(Permission::all());

        // 4️⃣ Create Super Admin user (if not exists)
        $superAdmin = User::firstOrCreate(
            ['email' => 'super-admin@ctpfsd.gop.pk'],
            [
                'name' => 'Super Admin',
                'cnic' => '0000000000000',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_department_user' => true,
            ]
        );

        // 5️⃣ Assign role to Super Admin user
        if (! $superAdmin->hasRole('super_admin')) {
            $superAdmin->assignRole('super_admin');
        }

        // 6️⃣ Optional logging
        $this->command->info('✅ Super Admin and default roles/permissions have been seeded successfully.');
    }
}
