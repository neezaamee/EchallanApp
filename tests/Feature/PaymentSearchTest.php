<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Payment;
use App\Models\MedicalRequest;
use App\Models\MedicalCenter;
use App\Models\Citizen;
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
        $center = new MedicalCenter();
        $center->name = 'Test Center';
        $center->address = 'Test Address';
        $center->save();

        // Create Citizen
        $citizen = new Citizen();
        $citizen->cnic = '33100-1234567-1';
        $citizen->name = 'John Doe';
        // Add other required fields if any (checking migration or model might be needed but assuming minimal for now)
        $citizen->father_name = 'Test Father'; // Guessing required fields
        $citizen->phone_number = '03001234567';
        $citizen->address = 'Test Address';
        $citizen->save();


        // Create Medical Request
        $request = new MedicalRequest();
        $request->psid = '999999';
        $request->amount = '500.00';
        $request->medical_center_id = $center->id;
        $request->citizen_id = $citizen->id;
        $request->status = 'completed'; // Assuming
        $request->save();

        // Create Payment
        $payment = new Payment();
        $payment->medical_request_id = $request->id;
        $payment->psid = '999999';
        $payment->amount = '500.00';
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
