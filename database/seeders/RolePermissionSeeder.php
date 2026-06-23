<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permission Modules
        $modules = [
            'medical-centers' => ['view', 'create', 'edit', 'delete'],
            'medical-requests' => ['view', 'create', 'edit', 'delete', 'approve'],
            'challans' => ['view', 'create', 'edit', 'delete', 'pay'],
            'warnings' => ['view', 'create', 'edit', 'delete'],
            'infrastructure' => ['view', 'create', 'edit', 'delete'],
            'provinces' => ['view', 'create', 'edit', 'delete'],
            'cities' => ['view', 'create', 'edit', 'delete'],
            'circles' => ['view', 'create', 'edit', 'delete'],
            'sectors' => ['view', 'create', 'edit', 'delete'],
            'dumping-points' => ['view', 'create', 'edit', 'delete'],
            'pick-up-points' => ['view', 'create', 'edit', 'delete'],
            'staff' => ['view', 'create', 'edit', 'delete'],
            'staff-postings' => ['view', 'create', 'edit', 'delete'],
            'reports' => ['view', 'finance', 'medical', 'lifter-squad'],
            'users' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'permissions' => ['view', 'create', 'edit', 'delete'],
            'logs' => ['view', 'audit'],
            'backups' => ['view', 'create', 'download', 'delete'],
            'settings' => ['view', 'edit'],
            'profile' => ['view', 'edit', 'password'],
        ];

        // 2. Create Permissions
        $allPermissionNames = [];
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$module}:{$action}";
                Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
                $allPermissionNames[] = $name;
            }
        }

        // 3. Define Role Allocations
        $roleDefinitions = [
            'super_admin' => $allPermissionNames, // Everything
            
            'admin' => [
                'medical-centers:view', 'medical-centers:create', 'medical-centers:edit',
                'staff:view', 'staff:create', 'staff:edit',
                'infrastructure:view', 'provinces:view', 'cities:view', 'circles:view', 'sectors:view', 'sectors:create', 'sectors:edit', 'sectors:delete', 'dumping-points:view', 'pick-up-points:view',
                'reports:view', 'logs:view',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'doctor' => [
                'medical-requests:view', 'medical-requests:create', 'medical-requests:edit', 'medical-requests:approve',
                'medical-centers:view',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'medical_assistant' => [
                'medical-requests:view', 'medical-requests:create', 'medical-requests:edit',
                'medical-centers:view',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'cto' => [
                'medical-requests:view', 'challans:view', 'warnings:view',
                'reports:view', 'reports:medical', 'reports:lifter-squad',
                'infrastructure:view', 'sectors:view',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'lifter_challan_officer' => [
                'challans:view', 'challans:create', 'challans:edit',
                'infrastructure:view', 'cities:view', 'circles:view', 'sectors:view', 'pick-up-points:view',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'warning_officer' => [
                'warnings:view', 'warnings:create',
                'infrastructure:view', 'cities:view', 'circles:view', 'sectors:view',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'duty_officer' => [
                'challans:view', 'challans:create',
                'infrastructure:view', 'pick-up-points:view',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'accountant' => [
                'challans:view', 'challans:pay',
                'reports:view', 'reports:finance',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'citizen' => [
                'medical-requests:view', 'medical-requests:create',
                'challans:view', 'challans:pay',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'deo' => [
                'medical-requests:view', 'medical-requests:create', 'medical-requests:edit',
                'challans:view', 'challans:create',
                'profile:view', 'profile:edit', 'profile:password'
            ],

            'ig' => ['reports:view', 'logs:audit', 'profile:view'],
            'dig' => ['reports:view', 'logs:audit', 'profile:view'],
            'addl_ig' => ['reports:view', 'logs:audit', 'profile:view'],
            'incharge' => ['medical-requests:view', 'challans:view', 'profile:view'],
            'reader' => ['reports:view', 'medical-requests:view', 'profile:view'],
            'circle_officer' => ['challans:view', 'infrastructure:view', 'profile:view'],
        ];

        // 4. Create Roles and Sync Permissions
        foreach ($roleDefinitions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($permissions);
            $this->command->info("Role '{$roleName}' synced with " . count($permissions) . " permissions.");
        }

        // 5. Create/Update Default Users
        $this->seedDefaultUsers();

        $this->command->info('Roles and permissions overhaul completed successfully!');
    }

    private function seedDefaultUsers(): void
    {
        // Super Admin
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
        $superAdmin->syncRoles(['super_admin']);

        // CTO (Admin Role)
        $cto = User::firstOrCreate(
            ['email' => 'cto@ctpfsd.gop.pk'],
            [
                'name' => 'Chief Traffic Officer',
                'cnic' => '1234567891234',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_department_user' => true,
            ]
        );
        $cto->syncRoles(['cto']);

        // Duty Officer (Testing Role)
        $dutyOfficer = User::firstOrCreate(
            ['email' => 'zaheerdutyofficer@gmail.com'],
            [
                'name' => 'Zaheer Duty Officer',
                'cnic' => '9999999999999',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
                'is_department_user' => true,
            ]
        );
        $dutyOfficer->syncRoles(['duty_officer']);
    }
}
