<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportsPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions if not existing in test sqlite
        $perms = [
            'reports:view',
            'reports:analytics',
            'reports:warnings',
            'reports:medical',
            'reports:challans',
            'reports:lifter-squad',
            'reports:finance',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        $superRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superRole->givePermissionTo($perms);
    }

    public function test_guest_is_redirected_to_login_for_reports(): void
    {
        $response = $this->get('/reports/analytics');
        $response->assertRedirect('/login');

        $response = $this->get('/reports/medical');
        $response->assertRedirect('/login');

        $response = $this->get('/reports/challans');
        $response->assertRedirect('/login');

        $response = $this->get('/reports/warnings');
        $response->assertRedirect('/login');
    }

    public function test_unauthorized_user_without_reports_permissions_gets_403(): void
    {
        $user = User::factory()->create();

        // User has no permissions, should be blocked with 403
        $this->actingAs($user)->get('/reports/analytics')->assertStatus(403);
        $this->actingAs($user)->get('/reports/medical')->assertStatus(403);
        $this->actingAs($user)->get('/reports/challans')->assertStatus(403);
        $this->actingAs($user)->get('/reports/warnings')->assertStatus(403);
        $this->actingAs($user)->get('/reports/finance')->assertStatus(403);
    }

    public function test_user_with_specific_permission_can_access_that_report(): void
    {
        // 1. User with only reports:medical
        $medicalUser = User::factory()->create();
        $medicalUser->givePermissionTo('reports:medical');

        $this->actingAs($medicalUser)->get('/reports/medical')->assertStatus(200);
        $this->actingAs($medicalUser)->get('/reports/warnings')->assertStatus(403);
        $this->actingAs($medicalUser)->get('/reports/challans')->assertStatus(403);

        // 2. User with only reports:warnings
        $warningUser = User::factory()->create();
        $warningUser->givePermissionTo('reports:warnings');

        $this->actingAs($warningUser)->get('/reports/warnings')->assertStatus(200);
        $this->actingAs($warningUser)->get('/reports/medical')->assertStatus(403);

        // 3. User with only reports:challans
        $challanUser = User::factory()->create();
        $challanUser->givePermissionTo('reports:challans');

        $this->actingAs($challanUser)->get('/reports/challans')->assertStatus(200);
        $this->actingAs($challanUser)->get('/reports/medical')->assertStatus(403);

        // 4. User with reports:finance
        $financeUser = User::factory()->create();
        $financeUser->givePermissionTo('reports:finance');

        $this->actingAs($financeUser)->get('/reports/finance')->assertStatus(200);
        $this->actingAs($financeUser)->get('/reports/payments/daily')->assertStatus(200);
        $this->actingAs($financeUser)->get('/reports/medical')->assertStatus(403);
    }

    public function test_super_admin_has_access_to_all_reports(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $this->actingAs($superAdmin)->get('/reports/analytics')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/reports/medical')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/reports/challans')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/reports/warnings')->assertStatus(200);
        $this->actingAs($superAdmin)->get('/reports/finance')->assertStatus(200);
    }
}
