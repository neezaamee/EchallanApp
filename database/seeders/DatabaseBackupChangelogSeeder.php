<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseBackupChangelogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('changelogs')->insert([
            [
                'version' => '1.5.0',
                'title' => 'Database Backup & Restore',
                'description' => 'Implemented comprehensive database backup system. Features include Manual Backup creation, Daily Automated Backups (01:00 AM), and Restore functionality (Super Admin only). Switched to PHP-native backup method for Shared Hosting compatibility.',
                'type' => 'feature',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
