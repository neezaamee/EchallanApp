<?php

use App\Models\Challan;
use App\Models\MedicalRequest;

echo "Starting Fee Migration for Unpaid Records...\n";

// 1. Update Traffic Challans
$bikeFee = config('fees.traffic.bike', 200);
$carFee = config('fees.traffic.car', 2000);

echo "Updating Bike Challans to {$bikeFee}...\n";
$bikeCount = Challan::where('payment_status', 'unpaid')
    ->whereIn('vehicle_type', ['motorcycle', 'bike'])
    ->update(['fine_amount' => $bikeFee]);

echo "Updating Car Challans to {$carFee}...\n";
$carCount = Challan::where('payment_status', 'unpaid')
    ->where('vehicle_type', 'car')
    ->update(['fine_amount' => $carFee]);

// 2. Update Medical Requests
$medicalFee = config('fees.medical', 200);
echo "Updating Medical Requests to {$medicalFee}...\n";
$medCount = MedicalRequest::where('payment_status', 'unpaid')
    ->update(['amount' => $medicalFee]);

echo "Migration Complete.\n";
echo "-------------------\n";
echo "Bike Challans Updated: $bikeCount\n";
echo "Car Challans Updated: $carCount\n";
echo "Medical Requests Updated: $medCount\n";
