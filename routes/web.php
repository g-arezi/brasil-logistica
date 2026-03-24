<?php

use App\Domains\User\Enums\UserProfileType;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('freight-board-page');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user?->profile_type === UserProfileType::Company) {
        return redirect()->route('company.dashboard');
    }

    return redirect()->route('driver.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'profile:company'])->group(function (): void {
    Route::get('/painel/empresa', function () {
        return view('dashboards.company');
    })->name('company.dashboard');
});

Route::middleware(['auth', 'verified', 'profile:driver'])->group(function (): void {
    Route::get('/painel/motorista', function () {
        return view('dashboards.driver');
    })->name('driver.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
