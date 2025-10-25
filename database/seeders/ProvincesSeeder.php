<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;

class ProvincesSeeder extends Seeder
{
    public function run()
    {
        Province::firstOrCreate(['name' => 'Punjab'], ['code' => 'PB']);
        // Add other provinces later if needed
    }
}
