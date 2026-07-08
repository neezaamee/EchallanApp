<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Staff;
use App\Models\StaffPosting;
use App\Models\Province;
use App\Models\City;
use App\Models\Circle;
use App\Models\Sector;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StaffPostingTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $province;
    protected $city;
    protected $circle;
    protected $sector;
    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->province = Province::create(['name' => 'Punjab', 'slug' => 'PB']);
        $this->city = City::create(['province_id' => $this->province->id, 'name' => 'Faisalabad', 'slug' => 'faisalabad', 'is_active' => true]);
        $this->circle = Circle::create(['city_id' => $this->city->id, 'name' => 'Head Quarter', 'slug' => 'head-quarter']);
        $this->sector = Sector::create(['circle_id' => $this->circle->id, 'name' => 'Sector 1', 'slug' => 'sector-1']);

        $this->staff = Staff::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'cnic' => '33100-1234567-1',
            'phone' => '03001234567',
            'email' => 'john.doe@example.com',
            'belt_no' => '12345',
            'gender' => 'male',
            'status' => 'active'
        ]);
    }

    public function test_can_create_circle_posting()
    {
        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\StaffPostings\CreatePosting::class)
            ->set('staff_id', $this->staff->id)
            ->set('location_type', 'circle')
            ->set('province_id', $this->province->id)
            ->set('city_id', $this->city->id)
            ->set('circle_id', $this->circle->id)
            ->set('start_date', '2026-07-05')
            ->call('save')
            ->assertRedirect(route('staff-postings.index'));

        $this->assertDatabaseHas('staff_postings', [
            'staff_id' => $this->staff->id,
            'circle_id' => $this->circle->id,
            'start_date' => '2026-07-05',
            'status' => 'active',
        ]);
    }

    public function test_can_create_sector_posting()
    {
        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\StaffPostings\CreatePosting::class)
            ->set('staff_id', $this->staff->id)
            ->set('location_type', 'sector')
            ->set('province_id', $this->province->id)
            ->set('city_id', $this->city->id)
            ->set('circle_id', $this->circle->id)
            ->set('sector_id', $this->sector->id)
            ->set('start_date', '2026-07-05')
            ->call('save')
            ->assertRedirect(route('staff-postings.index'));

        $this->assertDatabaseHas('staff_postings', [
            'staff_id' => $this->staff->id,
            'sector_id' => $this->sector->id,
            'start_date' => '2026-07-05',
            'status' => 'active',
        ]);
    }

    public function test_new_posting_deactivates_old_posting_correctly()
    {
        // 1. Create first posting starting 2026-07-01
        $oldPosting = StaffPosting::create([
            'staff_id' => $this->staff->id,
            'province_id' => $this->province->id,
            'start_date' => '2026-07-01',
            'status' => 'active'
        ]);

        // 2. Post staff to circle starting 2026-07-10
        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\StaffPostings\CreatePosting::class)
            ->set('staff_id', $this->staff->id)
            ->set('location_type', 'circle')
            ->set('province_id', $this->province->id)
            ->set('city_id', $this->city->id)
            ->set('circle_id', $this->circle->id)
            ->set('start_date', '2026-07-10')
            ->call('save');

        // 3. Verify old posting is now inactive and ends on 2026-07-09 (one day before the new one)
        $oldPosting->refresh();
        $this->assertEquals('inactive', $oldPosting->status);
        $this->assertEquals('2026-07-09', $oldPosting->end_date);
    }
}
