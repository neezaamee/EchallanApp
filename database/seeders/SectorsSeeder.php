<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Circle;
use App\Models\Sector;
use Illuminate\Support\Str;

class SectorsSeeder extends Seeder
{
    public function run()
    {
        $circles = Circle::all();

        foreach ($circles as $circle) {
            foreach (['Sector A', 'Sector B'] as $name) {
                $fullName = $circle->name . ' - ' . $name;
                Sector::firstOrCreate(
                    ['circle_id' => $circle->id, 'name' => $fullName],
                    ['slug' => Str::slug($fullName)]
                );
            }
        }

        $this->command->info('✅ Sectors seeded successfully!');
    }
}
