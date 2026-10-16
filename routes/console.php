<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('carts:delete-abandoned')->dailyAt('04:00');
