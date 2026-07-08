<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Challan;
use App\Models\DumpingPoint;
use App\Models\PickUpPoint;
use App\Models\Circle;
use App\Models\City;
use App\Models\Province;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChallanTableTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $dumpingPoint;
    protected $pickUpPoint;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $province = Province::create(['name' => 'Punjab', 'slug' => 'PB']);
        $city = City::create(['province_id' => $province->id, 'name' => 'Faisalabad', 'slug' => 'faisalabad', 'is_active' => true]);
        $circle = Circle::create(['city_id' => $city->id, 'name' => 'Jinnah Town', 'slug' => 'jinnah-town']);

        $this->dumpingPoint = DumpingPoint::create([
            'name' => 'Dumping Point A',
            'location' => 'FSD Location',
            'circle_id' => $circle->id
        ]);

        $this->pickUpPoint = PickUpPoint::create([
            'dumping_point_id' => $this->dumpingPoint->id,
            'name' => 'Pick Up Point 1',
            'location' => 'Main Road',
            'is_active' => true
        ]);
    }

    private static $counter = 0;

    private function createChallan($attributes = [])
    {
        self::$counter++;
        return Challan::create(array_merge([
            'officer_id' => $this->admin->id,
            'dumping_point_id' => $this->dumpingPoint->id,
            'pick_up_point_id' => $this->pickUpPoint->id,
            'violator_name' => 'John Doe',
            'violator_cnic' => '33100-1234567-1',
            'violator_mobile' => '03001234567',
            'vehicle_type' => 'car',
            'vehicle_number' => 'fsd-1234',
            'violation_name' => 'No Helmet',
            'fine_amount' => 500.00,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'psid' => '123456789' . sprintf('%03d', self::$counter)
        ], $attributes));
    }

    public function test_challan_table_shows_uppercase_vehicle_number()
    {
        $this->createChallan(['vehicle_number' => 'le-123-abc']);

        $response = $this->actingAs($this->admin)
            ->get(route('challans.index'));

        $response->assertStatus(200);
        $response->assertSee('LE-123-ABC');
    }

    public function test_challan_table_pagination_limit()
    {
        for ($i = 0; $i < 30; $i++) {
            $this->createChallan();
        }

        // Test default per page (50) -> should show all 30
        $response = $this->actingAs($this->admin)
            ->get(route('challans.index'));
        $response->assertStatus(200);
        $this->assertCount(30, $response->viewData('challans'));

        // Test custom per page = 20
        $response = $this->actingAs($this->admin)
            ->get(route('challans.index', ['per_page' => 20]));
        $response->assertStatus(200);
        $this->assertCount(20, $response->viewData('challans'));
    }

    public function test_challan_table_sorting()
    {
        $challan1 = $this->createChallan(['fine_amount' => 1000.00]);
        $challan2 = $this->createChallan(['fine_amount' => 200.00]);
        $challan3 = $this->createChallan(['fine_amount' => 750.00]);

        // Ascending sort by fine_amount
        $response = $this->actingAs($this->admin)
            ->get(route('challans.index', ['sort' => 'fine_amount', 'direction' => 'asc']));

        $response->assertStatus(200);
        $challans = $response->viewData('challans');
        $this->assertEquals(200.00, $challans[0]->fine_amount);
        $this->assertEquals(750.00, $challans[1]->fine_amount);
        $this->assertEquals(1000.00, $challans[2]->fine_amount);

        // Descending sort by fine_amount
        $response = $this->actingAs($this->admin)
            ->get(route('challans.index', ['sort' => 'fine_amount', 'direction' => 'desc']));

        $response->assertStatus(200);
        $challans = $response->viewData('challans');
        $this->assertEquals(1000.00, $challans[0]->fine_amount);
        $this->assertEquals(750.00, $challans[1]->fine_amount);
        $this->assertEquals(200.00, $challans[2]->fine_amount);
    }

    public function test_super_admin_can_delete_unpaid_challan()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $challan = $this->createChallan(['payment_status' => 'unpaid']);

        $response = $this->actingAs($superAdmin)
            ->delete(route('challans.destroy', $challan));

        $response->assertStatus(302);
        $this->assertSoftDeleted($challan);
    }

    public function test_super_admin_cannot_delete_paid_challan()
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $challan = $this->createChallan(['payment_status' => 'paid']);

        $response = $this->actingAs($superAdmin)
            ->delete(route('challans.destroy', $challan));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('challans', [
            'id' => $challan->id,
            'deleted_at' => null
        ]);
    }
}
