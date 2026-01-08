<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('ebr:delete-old-records')->everyMinute();
Schedule::command('backup:run')->daily()->at('03:00');
