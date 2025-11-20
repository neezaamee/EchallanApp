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
     *
     * This seeder creates:
     * 1. Default roles: admin, editor, user
     * 2. All necessary permissions
     * 3. Assigns permissions to roles
     * 4. Creates default users with roles
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $this->createPermissions();

        // Create roles and assign permissions
        $this->createRoles();

        // Create default users with roles
        $this->createDefaultUsers();

        $this->command->info('Roles and permissions seeded successfully!');
    }

    /**
     * Create all application permissions
     *
     * @return void
     */
    private function createPermissions(): void
    {
        // User management permissions
        $userPermissions = [
            'crud users',
            'create users',
            'read users',
            'update users',
            'delete users',
            'create user',
            'read user',
            'update user',
            'delete user',
        ];

        // Role and permission management
        $rolePermissions = [
            'crud permissions',
            'create permissions',
            'read permissions',
            'update permissions',
            'delete permissions',
            'crud roles',
            'create roles',
            'read roles',
            'update roles',
            'delete roles',
        ];

        // Content management permissions
        $provincePermissions = [
            'crud provinces',
            'create provinces',
            'read provinces',
            'update provinces',
            'delete provinces',
        ];

        // System management permissions
        $systemPermissions = [
            'view dashboard',
            'view admin dashboard',
            'view editor dashboard',
            'manage settings',
            'view logs',
            'manage backups',
        ];

        // City management permissions (from previous CRUD example)
        $cityPermissions = [
            'view cities',
            'create cities',
            'edit cities',
            'delete cities',
            'manage city status',
        ];

        // Combine all permissions
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $provincePermissions,
            $systemPermissions,
            $cityPermissions
        );

        // Create permissions
        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->command->info('Created ' . count($allPermissions) . ' permissions');
    }

    /**
     * Create roles and assign permissions
     *
     * @return void
     */
    private function createRoles(): void
    {
        // Admin role - has all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());
        //$superAdmin->givePermissionTo(Permission::all());
        $this->command->info('Created Super Admin role with all permissions');

        // Editor role - content management and some user management
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminPermissions = [
            // Provinces
            'create provinces',
            'read provinces',
        ];
        $adminRole->givePermissionTo($adminPermissions);
        $this->command->info('Created Admin role with content management permissions');


    }

    /**
     * Create default users with roles
     *
     * @return void
     */
    private function createDefaultUsers(): void
    {
        //Super Admin
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
        $superAdmin->assignRole('super_admin');

        //Admin
        $Admin = User::firstOrCreate(
            ['email' => 'cto@ctpfsd.gop.pk'],
            [
                'name' => 'Chief Traffic Officer',
                'cnic' => '1234567891234',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_department_user' => true,
            ]
        );

        $Admin->assignRole('admin');
        $this->command->info('Created Super Admin and Admin Users default users with roles assigned');

        /* // 1️⃣ Define roles
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
        $this->command->info('✅ Super Admin and default roles/permissions have been seeded successfully.'); */
    }
}
