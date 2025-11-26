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
        
        // Medical permissions
        $medicalCenterPermissions = [
            'crud medical centers',
            'create medical center',
            'read medical center',
            'update medical center',
            'delete medical center',
            'crud medical requests',
            'create medical request',
            'read medical request',
            'update medical request',
            'delete medical request',
            'crud medical request status',
            'create medical request status',
            'read medical request status',
            'update medical request status',
            'delete medical request status',
        ];
        // Lifter Squad permissions
        $lifterSquadPermissions = [
            'crud challans',
            'create challan',
            'read challan',
            'update challan',
            'delete challan',
        ];
        // Infrastructure permissions
        $infrastructurePermissions = [
            'crud infrastructure',
            'create infrastructure',
            'read infrastructure',
            'update infrastructure',
            'delete infrastructure',
            'crud provinces',
            'create province',
            'read province',
            'update province',
            'delete province',
            'crud cities',
            'create city',
            'read city',
            'update city',
            'delete city',
            'crud circles',
            'create circle',
            'read circle',
            'update circle',
            'delete circle',
            'crud dumping points',
            'create dumping point',
            'read dumping point',
            'update dumping point',
            'delete dumping point',
        ];
        // Staff permissions
        $staffPermissions = [
            'crud staff',
            'create staff',
            'read staff',
            'update staff',
            'delete staff',
            'crud staff postings',
            'create staff posting',
            'read staff posting',
            'update staff posting',
            'delete staff posting',
        ];
        // Reports permissions
        $reportsPermissions = [
            'crud finance reports',
            'create finance report',
            'read finance report',
            'update finance report',
            'delete finance report',
            'crud medical reports',
            'create medical report',
            'read medical report',
            'update medical report',
            'delete medical report',
            'crud lifter squad reports',
            'create lifter squad report',
            'read lifter squad report',
            'update lifter squad report',
            'delete lifter squad report',
        ];
        // Users management permissions
        $usersPermissions = [
            'crud users',
            'create users',
            'read users',
            'update users',
            'delete users',
            'crud user',
            'create user',
            'read user',
            'update user',
            'delete user',
        ];
        //Logs permissions
        $logsPermissions = [
            'crud logs',
            'create log',
            'read log',
            'update log',
            'delete log',
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
        // System management permissions
        $systemPermissions = [
            'crud settings',
            'crud backups',
        ];

        // Combine all permissions
        $allPermissions = array_merge(
            $medicalCenterPermissions,
            $lifterSquadPermissions,
            $infrastructurePermissions,
            $staffPermissions,
            $reportsPermissions,
            $usersPermissions,
            $logsPermissions,
            $rolePermissions,
            $systemPermissions
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

        // Admin role - content management and some user management
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminPermissions = [
            // Provinces
            'crud provinces',
            'create province',
            'read province',
            'update province',
            'delete province',
        ];
        $adminRole->givePermissionTo($adminPermissions);
        $this->command->info('Created Admin role with content management permissions');

        // Doctor role - content management and some user management
        $doctorRole = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctorPermissions = [
            'read user',
            'update user',
            'create medical request',
            'read medical request',
            'update medical request',
        ];
        $doctorRole->givePermissionTo($doctorPermissions);
        $this->command->info('Created Doctor role with content management permissions');

        // Citizen role - content management and some user management
        $citizenRole = Role::firstOrCreate(['name' => 'citizen', 'guard_name' => 'web']);
        $citizenPermissions = [
            'read user',
            'update user',
            'create medical request',
            'read medical request',
            'read challan',
        ];
        $citizenRole->givePermissionTo($citizenPermissions);
        $this->command->info('Created Citizen role with content management permissions');
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
