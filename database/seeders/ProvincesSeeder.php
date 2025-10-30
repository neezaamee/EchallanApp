<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvincesSeeder extends Seeder
{
    public function run()
    {
        $provinces = [
            ['name' => 'Punjab', 'code' => 'PB'],
            ['name' => 'Sindh', 'code' => 'SD'],
            ['name' => 'Khyber Pakhtunkhwa', 'code' => 'KP'],
            ['name' => 'Balochistan', 'code' => 'BL'],
            ['name' => 'Islamabad Capital Territory', 'code' => 'ICT'],
            ['name' => 'Gilgit-Baltistan', 'code' => 'GB'],
            ['name' => 'Azad Jammu and Kashmir', 'code' => 'AJK'],
        ];

        foreach ($provinces as $province) {
            Province::firstOrCreate(
                ['name' => $province['name']],
                ['code' => $province['code']]
            );
        }
    }
}
