<?php

use App\Models\Challan;
use App\Models\MedicalRequest;

$cnic = '3310028691827';

$challans = Challan::where('violator_cnic', $cnic)->get();
$medical = MedicalRequest::whereHas('citizen', function($q) use ($cnic) {
    $q->where('cnic', $cnic);
})->get();

echo "CNIC: $cnic\n";
echo "Challans Found: " . $challans->count() . "\n";
foreach($challans as $c) {
    echo " - PSID: {$c->psid}, Status: {$c->payment_status}\n";
}

echo "Medical Requests Found: " . $medical->count() . "\n";
foreach($medical as $m) {
    echo " - PSID: {$m->psid}, Status: {$m->payment_status}\n";
}
