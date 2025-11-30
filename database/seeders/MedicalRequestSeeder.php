<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRequest;
use App\Models\Citizen;
use App\Models\MedicalCenter;
use App\Models\User;
use Illuminate\Support\Str;

class MedicalRequestSeeder extends Seeder
{
    public function run()
    {
        $citizen = Citizen::first();
        $medicalCenter = MedicalCenter::first();
        $doctor = User::role('doctor')->first();

        if (!$citizen || !$medicalCenter) {
            $this->command->info('Please seed citizens and medical centers first.');
            return;
        }

        // 1. Pending Request (Unpaid)
        MedicalRequest::create([
            'citizen_id' => $citizen->id,
            'medical_center_id' => $medicalCenter->id,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'psid' => strtoupper(Str::random(10)),
            'amount' => 500,
            'created_by' => $citizen->user_id, // Created by citizen
        ]);

        // 2. Pending Request (Paid)
        MedicalRequest::create([
            'citizen_id' => $citizen->id,
            'medical_center_id' => $medicalCenter->id,
            'status' => 'pending',
            'payment_status' => 'paid',
            'psid' => strtoupper(Str::random(10)),
            'amount' => 500,
            'created_by' => $citizen->user_id,
        ]);

        // 3. Passed Request
        MedicalRequest::create([
            'citizen_id' => $citizen->id,
            'medical_center_id' => $medicalCenter->id,
            'status' => 'passed',
            'payment_status' => 'paid',
            'psid' => strtoupper(Str::random(10)),
            'amount' => 500,
            'doctor_action_by' => $doctor ? $doctor->id : null,
            'doctor_action_at' => now(),
            'created_by' => $citizen->user_id,
        ]);

        $this->command->info('Dummy medical requests seeded.');
    }
}
