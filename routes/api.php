<?php

declare(strict_types=1);

use App\Domains\Freight\Http\Controllers\Api\FreightController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/freights', [FreightController::class, 'index']);
    Route::post('/freights', [FreightController::class, 'store']);
});

