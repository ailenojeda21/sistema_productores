<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Purge soft-deleted records daily at 03:00
Schedule::command('app:purge-soft-deleted-records --days=365')
    ->dailyAt('03:00')
    ->appendOutputTo(storage_path('logs/purge-soft-deleted.log'));
