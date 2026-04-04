<?php

use App\Models\Challan;
use App\Models\MedicalRequest;

echo "PSID | Name | Amount | Type\n";
echo "-------------------------------\n";

$challans = Challan::where('violation_name', 'like', '%Test%')->latest()->limit(30)->get();
foreach ($challans as $c) {
    echo $c->psid . " | " . $c->violator_name . " | " . $c->fine_amount . " | Traffic (" . $c->vehicle_type . ")\n";
}

$medicals = MedicalRequest::with('citizen')->latest()->limit(30)->get();
foreach ($medicals as $m) {
    echo $m->psid . " | " . ($m->citizen->full_name ?? 'N/A') . " | " . $m->amount . " | Medical\n";
}
