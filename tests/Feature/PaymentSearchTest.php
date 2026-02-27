<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use App\Models\MedicalRequest;
use App\Models\MedicalCenter;
use App\Models\Citizen;
use App\Models\City;
use App\Models\Circle;
use App\Models\Province;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PaymentSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_search_payments_by_keyword()
    {
        // Create Admin User
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create Medical Center
        $province = Province::create(['name' => 'Punjab', 'slug' => 'PB']);
        $city = City::create(['province_id' => $province->id, 'name' => 'Lahore', 'slug' => 'lahore', 'is_active' => true]);
        $circle = Circle::create(['city_id' => $city->id, 'name' => 'Model Town', 'slug' => 'model-town']);

        $center = new MedicalCenter();
        $center->name = 'Test Center';
        $center->location = 'Test Address';
        $center->circle_id = $circle->id;
        $center->save();

        // Create Citizen
        $citizen = new Citizen();
        $citizen->cnic = '33100-1234567-1';
        $citizen->full_name = 'John Doe';
        // Add other required fields if any (checking migration or model might be needed but assuming minimal for now)
        $citizen->father_name = 'Test Father'; // Guessing required fields
        $citizen->phone = '03001234567';
        $citizen->save();


        // Create Medical Request
        $request = new MedicalRequest();
        $request->psid = '999999';
        $request->amount = 500.00;
        $request->medical_center_id = $center->id;
        $request->citizen_id = $citizen->id;
        $request->status = 'completed'; // Assuming
        $request->save();

        // Create Payment
        $payment = new Payment();
        $payment->medical_request_id = $request->id;
        $payment->psid = '999999';
        $payment->amount = 500.00;
        $payment->transaction_id = 'TXN12345';
        $payment->payment_method = 'credit_card';
        $payment->status = 'success';
        $payment->paid_at = now();
        $payment->save();

        // Act
        $response = $this->actingAs($admin)
            ->get(route('payments.search', ['keyword' => 'TXN12345']));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('TXN12345');
    }
}
