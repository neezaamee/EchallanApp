<?php

namespace Database\Seeders;

use App\Models\Changelog;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppChangelogV140Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $version = '1.4.0';
        $date = Carbon::now();

        $changes = [
            [
                'type' => 'added',
                'title' => 'Falcon v3.26.0 UI Refactor',
                'description' => 'Comprehensive UI standardization using Falcon v3.26.0 design system. Implemented card-wrapper pattern for all index tables and management pages.',
            ],
            [
                'type' => 'added',
                'title' => 'Global Soft-Badge System',
                'description' => 'Integrated Falcon\'s soft-badge system project-wide for consistent status visualization (Paid, Active, Pending, Released, etc.).',
            ],
            [
                'type' => 'changed',
                'title' => 'Table Standardization',
                'description' => 'Refactored over 15 modules to adopt unified table styling, including standardized actions, button placements, and typography.',
            ],
            [
                'type' => 'changed',
                'title' => 'Mobile Responsiveness',
                'description' => 'Improved mobile viewing experience across all management tables using Falcon\'s scrollbar and responsive utilities.',
            ],
            [
                'type' => 'fixed',
                'title' => 'Sorting Logic Errors',
                'description' => 'Resolved "Undefined variable $sortField" errors on Provinces and Cities pages. Cleaned up redundant Livewire components to prevent conflicts.',
            ],
            [
                'type' => 'fixed',
                'title' => 'Namespace & Path Fixes',
                'description' => 'Standardized namespaces and view paths for Livewire components to ensure reliable data rendering and performance.',
            ],
        ];

        foreach ($changes as $index => $change) {
            Changelog::create([
                'version' => $version,
                'release_date' => $date,
                'type' => $change['type'],
                'title' => $change['title'],
                'description' => $change['description'],
                'is_published' => true,
                'order' => $index,
            ]);
        }
    }
}
