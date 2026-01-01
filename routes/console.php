<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\BackupController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule Daily Backup
Schedule::call(function () {
    $controller = new BackupController();
    $controller->create();
})->dailyAt('01:00');
