<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// Schedule::command('tasks:auto-complete')->everyMinute(); //there is no overlap protection

Schedule::command('tasks:auto-complete')
    ->everyMinute()
    ->withoutOverlapping(10)
    // ->runInBackground()
    ->appendOutputTo(storage_path('logs/auto-complete.log'));