<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Circle;
use Illuminate\Support\Str;

class CirclesFaisalabadSeeder extends Seeder
{
    public function run()
    {
        // Ensure Faisalabad city exists (create if missing)
        $faisalabad = City::firstOrCreate(
            ['name' => 'Faisalabad'],
            ['slug' => Str::slug('Faisalabad'), 'province_id' => 1] // adjust province_id if needed
        );

        // Circles belonging to Faisalabad
        $circleNames = [
            'City',
            'Kotwali',
            'Civil Line',
            'Peoples Colony',
            'Madina Town',
            'Sadar',
            'Jaranwala',
        ];

        foreach ($circleNames as $name) {
            Circle::firstOrCreate(
                ['city_id' => $faisalabad->id, 'name' => $name],
                ['slug' => Str::slug($name)]
            );
        }

        $this->command->info('✅ Circles seeded successfully for Faisalabad!');
    }
}
