<?php

use App\Models\Challan;
use App\Models\MedicalRequest;
use App\Models\Citizen;
use App\Models\MedicalCenter;
use App\Models\DumpingPoint;
use App\Models\PickUpPoint;
use App\Services\BankService;
use Illuminate\Support\Str;

echo "Starting Test Data Generation...\n";

$names = [
    "Ahmad Ali", "M. Usman", "Zahid Khan", "Sara Bibi", "Fatima Zahra", "Bilal Ahmad", "Hamza Javed", "Zohaib Hassan", 
    "Ayesha Malik", "Irfan Aziz", "Sohail Anwar", "Nadia Gul", "Kashif Mehmood", "Tahira Parveen", "Waqar Younis", 
    "Shoaib Malik", "Babar Azam", "Rizwan Shah", "Shaheen Afridi", "Naseem Shah", "Shadab Khan", "Fakhar Zaman", 
    "Imam-ul-Haq", "Haris Rauf", "Asif Ali", "Khushdil Shah", "Iftikhar Ahmad", "Mohammad Nawaz", "Hassan Ali", "Farhan Saeed"
];

$vehicleNumbers = ["ABC-1234", "LEE-5678", "FSD-9900", "LHR-1122", "KHI-4455", "ISL-7788", "RWP-3344", "MUL-2233", "GUJ-6677", "SIA-8899"];

// 1. Generate 30 Traffic Challans
echo "Generating 30 Traffic Challans...\n";
$dumpingPoint = DumpingPoint::first();
$pickUpPoint = PickUpPoint::first();
$officerId = 1; // Assuming first user is admin/officer

for ($i = 0; $i < 30; $i++) {
    $name = $names[$i % count($names)] . " " . ($i > 29 ? "Test" : "");
    $isCar = $i % 2 == 0;
    $head = $isCar ? 'TRAFFIC_CAR' : 'TRAFFIC_BIKE';
    $amount = $isCar ? config('fees.traffic.car', 2000) : config('fees.traffic.bike', 200);
    
    $psid = BankService::generatePsid($head, $amount);
    
    Challan::create([
        'officer_id' => $officerId,
        'dumping_point_id' => $dumpingPoint?->id,
        'pick_up_point_id' => $pickUpPoint?->id,
        'violator_name' => $name,
        'violator_cnic' => '33100' . rand(10000000, 99999999),
        'violator_mobile' => '0300' . rand(1000000, 9999999),
        'vehicle_type' => $isCar ? 'car' : 'bike',
        'vehicle_number' => $vehicleNumbers[rand(0, 9)],
        'violation_name' => 'Over Speeding (Test)',
        'fine_amount' => $amount,
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'psid' => $psid,
    ]);
}

// 2. Generate 30 Medical Requests
echo "Generating 30 Medical Requests...\n";
$medicalCenter = MedicalCenter::first();

for ($i = 0; $i < 30; $i++) {
    $name = $names[($i + 5) % count($names)] . " " . ($i > 29 ? "Test" : "");
    $cnic = '33102' . rand(10000000, 99999999);
    
    // Create shadow citizen
    $citizen = Citizen::firstOrCreate(
        ['cnic' => $cnic],
        [
            'full_name' => $name,
            'father_name' => 'F. ' . $name,
            'phone' => '0321' . rand(1000000, 9999999),
            'gender' => ($i % 3 == 0) ? 'female' : 'male',
            'role_id' => 4,
        ]
    );
    
    $medicalFee = config('fees.medical', 200);
    $psid = BankService::generatePsid('MEDICAL', $medicalFee);
    
    MedicalRequest::create([
        'citizen_id' => $citizen->id,
        'medical_center_id' => $medicalCenter?->id,
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'psid' => $psid,
        'amount' => $medicalFee,
        'created_by' => $officerId,
    ]);
}

echo "Generation Complete. 60 Records created.\n";
