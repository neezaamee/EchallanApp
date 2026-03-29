<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Changelog;

class ChangelogV150Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $version = '1.5.0';
        $releaseDate = '2026-03-29';

        $changes = [
            [
                'type' => 'added',
                'title' => 'Standardized Fee Management',
                'description' => 'Centralized all system fees in config/fees.php (Bike: 200, Car: 2000, Medical: 200).',
                'order' => 1
            ],
            [
                'type' => 'added',
                'title' => 'Bank-Grade PSID System',
                'description' => 'New 20-digit PSID structure with Luhn algorithm and city-aware tracking (Category-CityCode-Date-Random-CheckDigit).',
                'order' => 2
            ],
            [
                'type' => 'added',
                'title' => 'Citizen Dashboard 2.0',
                'description' => 'Unified view for all Traffic and Medical records using CNIC-based aggregation and 1Link payment instructions.',
                'order' => 3
            ],
            [
                'type' => 'changed',
                'title' => 'Jurisdictional Security Refinement',
                'description' => 'Agnostic location access for citizens to ensure dropdowns load correctly.',
                'order' => 4
            ],
            [
                'type' => 'fixed',
                'title' => 'Citizen Search & Details',
                'description' => 'Fixed RouteNotFoundException for vehicle search and blank pages in challan details.',
                'order' => 5
            ],
        ];

        foreach ($changes as $change) {
            Changelog::updateOrCreate(
                [
                    'version' => $version,
                    'title' => $change['title']
                ],
                [
                    'release_date' => $releaseDate,
                    'type' => $change['type'],
                    'description' => $change['description'],
                    'is_published' => true,
                    'order' => $change['order'],
                ]
            );
        }
    }
}
