<?php

namespace Database\Seeders;

use App\Models\Changelog;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UpdateChangelogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $version = '1.0.1'; // Assuming increment from 1.0.0
        $date = Carbon::now();

        $changes = [
            [
                'type' => 'added',
                'title' => 'Cash Payment Support',
                'description' => 'Added "Cash" payment method for medical requests. Updates payment processing to handle cash transactions directly.',
            ],
            [
                'type' => 'changed',
                'title' => 'User & Staff Syncing',
                'description' => 'Enhanced StaffObserver to automatically sync Name, CNIC, and Email from Staff records to linked User accounts. Resticted direct editing of these fields in User Management.',
            ],
            [
                'type' => 'added',
                'title' => 'Plain Password Storage',
                'description' => 'Added capability to store and view plain text passwords for Super Admins. Added "Stored Password" field to User Show view.',
            ],
            [
                'type' => 'changed',
                'title' => 'User Management UI Refactor',
                'description' => 'Refactored User Index and Show views to match the "Cities" module layout. Implemented clean table design, card layout, and search functionality.',
            ],
            [
                'type' => 'fixed',
                'title' => 'Pagination Styling',
                'description' => 'Fixed pagination styling issues by configuring Bootstrap 5 as the default paginator style.',
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
