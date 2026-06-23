<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Changelog;
use Carbon\Carbon;

class ChangelogV170Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
     {
         $version = '1.7.0';
         $releaseDate = '2026-06-14';

         $changes = [
             [
                 'type' => 'added',
                 'title' => 'Sectors Management (CRUD)',
                 'description' => 'Introduced the "Sector" entity under Circles. Added full CRUD functionality (listing, adding, editing) with role-based permissions (sectors:view, sectors:create, sectors:edit, sectors:delete).',
                 'order' => 1
             ],
             [
                 'type' => 'added',
                 'title' => 'Sectors Sidebar Navigation',
                 'description' => 'Integrated the "Sectors" menu option under the Infrastructure dropdown in the side vertical navigation bar, complete with active-state matching rules.',
                 'order' => 2
             ],
             [
                 'type' => 'changed',
                 'title' => 'Warning Officer Postings to Sectors',
                 'description' => 'Redefined Warning Officers to be posted at Sectors (which belong to Circles) instead of Dumping Points. Updated staff posting creation, history log, badges, and dashboard layout to support Sector postings.',
                 'order' => 3
             ],
             [
                 'type' => 'fixed',
                 'title' => 'Persistent Receipt Number Generation',
                 'description' => 'Resolved a dynamic date bug on receipts by adding a persistent "receipt_number" column to the payments table (RCP-YYYYMMDD-XXXXX). Updated payment controllers, models, and both standard and thermal receipt views to use the stored receipt number.',
                 'order' => 4
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
