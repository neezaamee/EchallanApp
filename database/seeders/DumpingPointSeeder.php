<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Circle;
use App\Models\DumpingPoint;

class DumpingPointSeeder extends Seeder
{
    public function run(): void
    {
        // Loop through each circle and create 3 dumping points for it
        Circle::all()->each(function ($circle) {
            for ($i = 1; $i <= 3; $i++) {
                DumpingPoint::create([
                    'name' => "Dumping Point {$i} for {$circle->name}",
                    'location' => 'Sample Location for testing',
                    'circle_id' => $circle->id,
                ]);
            }
        });
    }
}
