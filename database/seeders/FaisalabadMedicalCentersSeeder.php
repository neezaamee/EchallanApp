<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Circle;
use App\Models\MedicalCenter;
use Illuminate\Database\Seeder;

class FaisalabadMedicalCentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Loop through each circle and create 3 dumping points for it
        Circle::all()->each(function ($circle) {
            for ($i = 1; $i <= 3; $i++) {
                MedicalCenter::create([
                    'name' => "Medical Center {$i} for {$circle->name}",
                    'location' => 'Sample Location for testing',
                    'circle_id' => $circle->id,
                ]);
            }
        });
    }
}
