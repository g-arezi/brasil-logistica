<?php

use App\Domains\Freight\Models\Freight;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Freight::whereNotNull('expires_at')
        ->where('expires_at', '<=', now())
        ->delete();
})->daily();
