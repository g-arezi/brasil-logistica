<?php

declare(strict_types=1);

use App\Domains\Freight\Http\Controllers\Api\FreightController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::middleware('throttle:120,1')->get('/freights', [FreightController::class, 'index']);

    Route::middleware(['auth', 'verified', 'throttle:30,1'])->group(function (): void {
        Route::post('/freights', [FreightController::class, 'store']);
    });
});
