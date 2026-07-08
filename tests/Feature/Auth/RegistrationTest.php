<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSeeLivewire('forms.registration');
    }

    public function test_new_users_can_register(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'father_name' => 'Father Name',
            'gender' => 'male',
            'cnic' => '3310221557297',
            'phone' => '03022211000',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));
    }
}
