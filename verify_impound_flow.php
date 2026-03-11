<?php

use App\Models\User;
use App\Models\Challan;
use App\Models\Staff;
use App\Models\StaffPosting;
use App\Models\DumpingPoint;
use App\Models\PickUpPoint;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 1. Setup Role and User
$roleName = 'duty_officer';
$role = Role::firstOrCreate(['name' => $roleName]);

$user = User::firstOrCreate(
    ['email' => 'duty_test@example.com'],
    [
        'name' => 'Duty Officer Test',
        'cnic' => '3520100000000',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
    ]
);
$user->assignRole($role);

$staff = Staff::firstOrCreate(
    ['user_id' => $user->id],
    [
        'id' => 999, // Specific ID for testing
        'first_name' => 'Duty',
        'last_name' => 'Officer',
        'cnic' => '1234567890123',
        'phone' => '03001234567',
        'status' => 'active',
    ]
);

// 2. Setup Location
$dumpingPoint = DumpingPoint::first(); // Use existing
if (!$dumpingPoint) {
    echo "No dumping point found. Please seed your database first.\n";
    exit;
}

$pickUpPoint = PickUpPoint::where('dumping_point_id', $dumpingPoint->id)->first();
if (!$pickUpPoint) {
    $pickUpPoint = PickUpPoint::create([
        'dumping_point_id' => $dumpingPoint->id,
        'name' => 'Test Pickup',
        'location' => 'Test Location',
        'is_active' => true,
    ]);
}

// 3. Assign Staff to Dumping Point
StaffPosting::updateOrCreate(
    ['staff_id' => $staff->id, 'status' => 'active'],
    [
        'dumping_point_id' => $dumpingPoint->id,
        'start_date' => now(),
    ]
);

// 4. Create Bounded Challans
// One Paid, One Unpaid
$psidPaid = '1000' . time();
Challan::create([
    'officer_id' => 1, // System admin
    'dumping_point_id' => $dumpingPoint->id,
    'pick_up_point_id' => $pickUpPoint->id,
    'violator_name' => 'Paid Violator',
    'violator_cnic' => '3520111111111',
    'violator_mobile' => '03451111111',
    'vehicle_type' => 'car',
    'vehicle_number' => 'ABC-123',
    'violation_name' => 'Wrong Parking',
    'fine_amount' => 2000,
    'status' => 'pending',
    'payment_status' => 'paid',
    'psid' => $psidPaid,
]);

$psidUnpaid = '1001' . time();
Challan::create([
    'officer_id' => 1,
    'dumping_point_id' => $dumpingPoint->id,
    'pick_up_point_id' => $pickUpPoint->id,
    'violator_name' => 'Unpaid Violator',
    'violator_cnic' => '3520122222222',
    'violator_mobile' => '03452222222',
    'vehicle_type' => 'motorcycle',
    'vehicle_number' => 'XYZ-999',
    'violation_name' => 'No Parking',
    'fine_amount' => 500,
    'status' => 'pending',
    'payment_status' => 'unpaid',
    'psid' => $psidUnpaid,
]);

echo "Verification data created successfully!\n";
echo "Duty Officer User: duty_test@example.com / password\n";
echo "Dumping Point: " . $dumpingPoint->name . "\n";
echo "Paid PSID: $psidPaid\n";
echo "Unpaid PSID: $psidUnpaid\n";
