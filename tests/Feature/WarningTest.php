<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Warning;
use App\Models\Challan;
use App\Models\DumpingPoint;
use App\Models\PickUpPoint;
use App\Models\Circle;
use App\Models\City;
use App\Models\Province;
use App\Models\Sector;
use App\Models\Staff;
use App\Models\StaffPosting;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarningTest extends TestCase
{
    use RefreshDatabase;

    protected $warningOfficer;
    protected $citizen;
    protected $circle;
    protected $sector;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->warningOfficer = User::factory()->create();
        $this->warningOfficer->assignRole('warning_officer');

        $this->citizen = User::factory()->create();
        $this->citizen->assignRole('citizen');

        $province = Province::create(['name' => 'Punjab', 'slug' => 'PB']);
        $city = City::create(['province_id' => $province->id, 'name' => 'Lahore', 'slug' => 'lahore', 'is_active' => true]);
        $this->circle = Circle::create(['city_id' => $city->id, 'name' => 'Model Town', 'slug' => 'model-town']);
        $this->sector = Sector::create(['circle_id' => $this->circle->id, 'name' => 'Sector A', 'slug' => 'sector-a']);

        $staff = Staff::create([
            'user_id' => $this->warningOfficer->id,
            'first_name' => 'Warning',
            'last_name' => 'Officer',
            'cnic' => '33100-9999999-1',
            'phone' => '03009999999',
            'email' => $this->warningOfficer->email,
            'belt_no' => '54321',
            'gender' => 'male',
            'status' => 'active'
        ]);

        StaffPosting::create([
            'staff_id' => $staff->id,
            'province_id' => $province->id,
            'city_id' => $city->id,
            'circle_id' => $this->circle->id,
            'sector_id' => $this->sector->id,
            'start_date' => now(),
            'status' => 'active',
        ]);
    }

    public function test_unauthorized_user_cannot_access_warning_create_form()
    {
        $response = $this->actingAs($this->citizen)
            ->get(route('warnings.create'));

        $response->assertStatus(403);
    }

    public function test_authorized_user_can_access_warning_create_form()
    {
        $response = $this->actingAs($this->warningOfficer)
            ->get(route('warnings.create'));

        $response->assertStatus(200);
    }

    public function test_officer_can_issue_warning()
    {
        \Livewire\Livewire::actingAs($this->warningOfficer)
            ->test(\App\Livewire\Warnings\Create::class)
            ->set('violator_name', 'Jane Doe')
            ->set('violator_cnic', '33100-7654321-1')
            ->set('violator_mobile', '0312-7654321')
            ->set('vehicle_type', 'car')
            ->set('vehicle_number', 'LHR-777')
            ->set('violation_name', 'Over speeding')
            ->set('location', 'Main Boulevard')
            ->set('remarks', 'Warned for speeding.')
            ->call('save')
            ->assertRedirect(route('warnings.index'));

        $this->assertDatabaseHas('warnings', [
            'violator_name' => 'Jane Doe',
            'violator_cnic' => '33100-7654321-1',
            'vehicle_number' => 'LHR-777',
            'violation_name' => 'Over speeding',
            'officer_id' => $this->warningOfficer->id,
            'circle_id' => $this->circle->id,
            'sector_id' => $this->sector->id,
        ]);
    }

    public function test_violator_history_component_finds_warnings_and_challans()
    {
        // Issue a warning
        Warning::create([
            'officer_id' => $this->warningOfficer->id,
            'violator_name' => 'Jane Doe',
            'violator_cnic' => '33100-7654321-1',
            'violator_mobile' => '0312-7654321',
            'vehicle_type' => 'car',
            'vehicle_number' => 'LHR-777',
            'violation_name' => 'Over speeding',
            'location' => 'Main Boulevard',
            'remarks' => 'Warned.',
        ]);

        // Issue a challan
        $dumpingPoint = DumpingPoint::create(['circle_id' => $this->circle->id, 'name' => 'Dumping Point A', 'location' => 'Loc A']);
        $pickUpPoint = PickUpPoint::create(['dumping_point_id' => $dumpingPoint->id, 'name' => 'Pick Point 1']);

        Challan::create([
            'officer_id' => $this->warningOfficer->id,
            'dumping_point_id' => $dumpingPoint->id,
            'pick_up_point_id' => $pickUpPoint->id,
            'violator_name' => 'Jane Doe',
            'violator_cnic' => '33100-7654321-1',
            'violator_mobile' => '0312-7654321',
            'vehicle_type' => 'car',
            'vehicle_number' => 'LHR-777',
            'violation_name' => 'Wrong Parking',
            'fine_amount' => 2000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'psid' => '20002220614123456789',
        ]);

        // Test component
        \Livewire\Livewire::test(\App\Livewire\ViolatorHistory::class)
            ->call('checkHistory', '33100-7654321-1', 'LHR-777')
            ->assertSet('hasHistory', true)
            ->assertCount('warnings', 1)
            ->assertCount('challans', 1);
    }

    public function test_authorized_user_can_access_warning_edit_form()
    {
        $this->warningOfficer->givePermissionTo('warnings:edit');

        $warning = Warning::create([
            'officer_id' => $this->warningOfficer->id,
            'violator_name' => 'Jane Doe',
            'violator_cnic' => '33100-7654321-1',
            'vehicle_type' => 'car',
            'vehicle_number' => 'LHR-777',
            'violation_name' => 'Over speeding',
        ]);

        $response = $this->actingAs($this->warningOfficer)
            ->get(route('warnings.edit', $warning->id));

        $response->assertStatus(200);
    }

    public function test_officer_can_edit_warning()
    {
        $this->warningOfficer->givePermissionTo('warnings:edit');

        $warning = Warning::create([
            'officer_id' => $this->warningOfficer->id,
            'violator_name' => 'Jane Doe',
            'violator_cnic' => '33100-7654321-1',
            'vehicle_type' => 'car',
            'vehicle_number' => 'LHR-777',
            'violation_name' => 'Over speeding',
        ]);

        \Livewire\Livewire::actingAs($this->warningOfficer)
            ->test(\App\Livewire\Warnings\Edit::class, ['id' => $warning->id])
            ->set('violator_name', 'Jane Smith')
            ->call('update')
            ->assertRedirect(route('warnings.index'));

        $this->assertDatabaseHas('warnings', [
            'id' => $warning->id,
            'violator_name' => 'Jane Smith',
        ]);
    }

    public function test_officer_can_delete_warning()
    {
        $this->warningOfficer->givePermissionTo('warnings:delete');

        $warning = Warning::create([
            'officer_id' => $this->warningOfficer->id,
            'violator_name' => 'Jane Doe',
            'violator_cnic' => '33100-7654321-1',
            'vehicle_type' => 'car',
            'vehicle_number' => 'LHR-777',
            'violation_name' => 'Over speeding',
        ]);

        \Livewire\Livewire::actingAs($this->warningOfficer)
            ->test(\App\Livewire\Warnings\Index::class)
            ->call('confirmDelete', $warning->id)
            ->assertSet('confirmingWarningDeletion', true)
            ->assertSet('deleteId', $warning->id)
            ->call('deleteWarning');

        $this->assertSoftDeleted('warnings', [
            'id' => $warning->id,
        ]);
    }
}
