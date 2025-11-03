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
            ProvincesSeeder::class,
            CitiesPunjabSeeder::class,
            CirclesFaisalabadSeeder::class,
            DumpingPointSeeder::class,
            DesignationsSeeder::class,
            RanksSeeder::class,
            RoleSeeder::class,
            SuperAdminSeeder::class,
        ]);

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
    }
}
