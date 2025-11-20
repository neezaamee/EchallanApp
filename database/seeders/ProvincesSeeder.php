<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvincesSeeder extends Seeder
{
    public function run()
    {
        $provinces = [
            ['name' => 'Punjab', 'slug' => 'PB'],
            ['name' => 'Sindh', 'slug' => 'SD'],
            ['name' => 'Khyber Pakhtunkhwa', 'slug' => 'KP'],
            ['name' => 'Balochistan', 'slug' => 'BL'],
            ['name' => 'Islamabad Capital Territory', 'slug' => 'ICT'],
            ['name' => 'Gilgit-Baltistan', 'slug' => 'GB'],
            ['name' => 'Azad Jammu and Kashmir', 'slug' => 'AJK'],
        ];

        foreach ($provinces as $province) {
            Province::firstOrCreate(
                ['name' => $province['name']],
                ['slug' => $province['slug']]
            );
        }
    }
}
