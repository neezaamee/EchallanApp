<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MedicalRequest;
use App\Models\MedicalCenter;
use App\Models\Citizen;
use App\Models\City;
use App\Models\Circle;
use App\Models\Province;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalRequestDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;
    protected $center;
    protected $citizen;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('super_admin');

        $province = Province::create(['name' => 'Punjab', 'slug' => 'PB']);
        $city = City::create(['province_id' => $province->id, 'name' => 'Lahore', 'slug' => 'lahore', 'is_active' => true]);
        $circle = Circle::create(['city_id' => $city->id, 'name' => 'Model Town', 'slug' => 'model-town']);

        $this->center = new MedicalCenter();
        $this->center->name = 'Test Center';
        $this->center->location = 'Test Address';
        $this->center->circle_id = $circle->id;
        $this->center->save();

        $this->citizen = new Citizen();
        $this->citizen->cnic = '33100-1234567-1';
        $this->citizen->full_name = 'John Doe';
        $this->citizen->father_name = 'Test Father';
        $this->citizen->phone = '03001234567';
        $this->citizen->save();
    }

    private function createMedicalRequest($attributes = [])
    {
        return MedicalRequest::create(array_merge([
            'psid' => '123456789012345678',
            'amount' => 200.00,
            'medical_center_id' => $this->center->id,
            'citizen_id' => $this->citizen->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'created_by' => $this->superAdmin->id,
        ], $attributes));
    }

    public function test_super_admin_can_delete_unpaid_medical_request()
    {
        $request = $this->createMedicalRequest(['payment_status' => 'unpaid']);

        $response = $this->actingAs($this->superAdmin)
            ->delete(route('medical-requests.destroy', $request->id));

        $response->assertStatus(302);
        $this->assertSoftDeleted($request);
    }

    public function test_super_admin_cannot_delete_paid_medical_request()
    {
        $request = $this->createMedicalRequest(['payment_status' => 'paid']);

        $response = $this->actingAs($this->superAdmin)
            ->delete(route('medical-requests.destroy', $request->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('medical_requests', [
            'id' => $request->id,
            'deleted_at' => null
        ]);
    }
}
