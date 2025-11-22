<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RolePermissionSeeder::class,
            RanksSeeder::class,
            ProvincesSeeder::class,
            CitiesPunjabSeeder::class,
            CirclesFaisalabadSeeder::class,
            DumpingPointSeeder::class,
            FaisalabadMedicalCentersSeeder::class,
            DesignationsSeeder::class,
            StaffSeeder::class,
            FaisalabadStaffPostingSeeder::class,
            CitizensSeeder::class,
        ]);

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
    }
}
