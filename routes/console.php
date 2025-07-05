<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('ebr:delete-old-records')->everyFiveMinutes();
Schedule::command('backup:run')->daily()->at('03:00');
