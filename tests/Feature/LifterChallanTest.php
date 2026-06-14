<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Challan;
use App\Models\DumpingPoint;
use App\Models\PickUpPoint;
use App\Models\Staff;
use App\Models\StaffPosting;
use App\Models\Circle;
use App\Models\City;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LifterChallanTest extends TestCase
{
    use RefreshDatabase;

    public function test_officer_can_issue_challan()
    {
        // Setup Dependencies
        $province = Province::create(['name' => 'Punjab', 'slug' => 'PB']);
        $city = City::create(['province_id' => $province->id, 'name' => 'Lahore', 'slug' => 'lahore', 'is_active' => true]);
        $circle = Circle::create(['city_id' => $city->id, 'name' => 'Model Town', 'slug' => 'model-town']);
        
        $dumpingPoint = DumpingPoint::create(['circle_id' => $circle->id, 'name' => 'Dumping Point A', 'location' => 'Loc A']);
        $pickUpPoint = PickUpPoint::create(['dumping_point_id' => $dumpingPoint->id, 'name' => 'Pick Point 1']);

        // Create Officer
        \Spatie\Permission\Models\Role::create(['name' => 'lifter_challan_officer']);
        $officer = User::factory()->create();
        $officer->assignRole('lifter_challan_officer'); // Assuming role exists or we skip middleware for unit test logic, but let's try with role
        
        $staff = Staff::create([
            'user_id' => $officer->id, 
            'files_number' => '123',
            'first_name' => 'Officer',
            'cnic' => '11111-1111111-1'
        ]);
        StaffPosting::create([
            'staff_id' => $staff->id,
            'dumping_point_id' => $dumpingPoint->id,
            'status' => 'active',
            'start_date' => now(),
        ]);

        // Act & Assert using Livewire component test
        \Livewire\Livewire::actingAs($officer)
            ->test(\App\Livewire\Challans\Create::class)
            ->set('dumping_point_id', $dumpingPoint->id)
            ->set('pick_up_point_id', $pickUpPoint->id)
            ->set('violator_name', 'John Doe')
            ->set('violator_cnic', '33100-1234567-1')
            ->set('violator_mobile', '0300-1234567')
            ->set('vehicle_type', 'car')
            ->set('vehicle_number', 'LEC-1234')
            ->set('violation', 'Wrong Parking')
            ->call('save')
            ->assertRedirect(route('challans.index'));

        $this->assertDatabaseHas('challans', [
            'vehicle_number' => 'LEC-1234',
            'fine_amount' => 2000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }

    public function test_officer_can_verify_payment_and_release_vehicle()
    {
         // Setup Dependencies
        $province = Province::create(['name' => 'Punjab', 'slug' => 'PB']);
        $city = City::create(['province_id' => $province->id, 'name' => 'Lahore', 'slug' => 'lahore', 'is_active' => true]);
        $circle = Circle::create(['city_id' => $city->id, 'name' => 'Model Town', 'slug' => 'model-town']);
        
        $dumpingPoint = DumpingPoint::create(['circle_id' => $circle->id, 'name' => 'Dumping Point A', 'location' => 'Loc A']);
        $pickUpPoint = PickUpPoint::create(['dumping_point_id' => $dumpingPoint->id, 'name' => 'Pick Point 1']);

        $officer = User::factory()->create();
        $challan = Challan::create([
            'officer_id' => $officer->id,
            'dumping_point_id' => $dumpingPoint->id,
            'pick_up_point_id' => $pickUpPoint->id,
            'violator_name' => 'John Doe',
            'violator_cnic' => '33100-1234567-1',
            'violator_mobile' => '0300-1234567',
            'vehicle_type' => 'car',
            'vehicle_number' => 'LEC-1234',
            'violation_name' => 'Wrong Parking',
            'fine_amount' => 2000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'psid' => '20002220614123456789',
        ]);

        // Act: Verify Payment
        $response = $this->actingAs($officer)
            ->post(route('challans.validate-payment', $challan), [
                'transaction_id' => 'TXN-999',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('paid', $challan->fresh()->status);
        $this->assertEquals('TXN-999', $challan->fresh()->transaction_id);

        // Act: Release Vehicle
        $response = $this->actingAs($officer)
            ->post(route('impound.release.submit', $challan), [
                'receiver_name' => 'John Receiver',
                'receiver_cnic' => '3310012345671',
                'receiver_father_name' => 'Father Receiver',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('released', $challan->fresh()->status);
    }
}
