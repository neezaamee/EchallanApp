<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'reports:view',
            'reports:analytics',
            'reports:warnings',
            'reports:medical',
            'reports:challans',
            'reports:lifter-squad',
            'reports:finance',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // Grant all reports permissions to super_admin
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        // Grant relevant reports permissions to admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo([
                'reports:view',
                'reports:analytics',
                'reports:warnings',
                'reports:medical',
                'reports:challans',
                'reports:finance',
            ]);
        }

        // Grant relevant reports permissions to cto
        $cto = Role::where('name', 'cto')->first();
        if ($cto) {
            $cto->givePermissionTo([
                'reports:view',
                'reports:analytics',
                'reports:warnings',
                'reports:medical',
                'reports:challans',
                'reports:lifter-squad',
            ]);
        }

        // Grant financial reports permissions to accountant
        $accountant = Role::where('name', 'accountant')->first();
        if ($accountant) {
            $accountant->givePermissionTo([
                'reports:view',
                'reports:finance',
            ]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Keep core permissions intact or remove added ones if needed
        Permission::whereIn('name', [
            'reports:analytics',
            'reports:challans',
        ])->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
