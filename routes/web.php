<?php

use App\Domains\User\Enums\UserProfileType;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/fretes', function () {
    return view('freight-board-page');
})->name('freights.board');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user?->profile_type === UserProfileType::Admin) {
        return redirect()->route('admin.dashboard');
    }

    if (
        $user?->profile_type === UserProfileType::Transportadora
        || $user?->profile_type === UserProfileType::Company
        || $user?->profile_type === UserProfileType::FreightistaLegacy
    ) {
        return redirect()->route('transportadora.dashboard');
    }

    if ($user?->profile_type === UserProfileType::Agenciador) {
        return redirect()->route('agenciador.dashboard');
    }

    return redirect()->route('driver.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'profile:admin'])->group(function (): void {
    Route::get('/painel/admin', function () {
        return view('dashboards.admin');
    })->name('admin.dashboard');
});

Route::middleware(['auth', 'verified', 'profile:transportadora,company,freightista'])->group(function (): void {
    Route::get('/painel/transportadora', function () {
        return view('dashboards.transportadora');
    })->name('transportadora.dashboard');

    // Alias legado de nome para não quebrar links antigos.
    Route::get('/painel/fretista', function () {
        return redirect()->route('transportadora.dashboard');
    })->name('freightista.dashboard');

    // Rota legada para compatibilidade com a fase anterior.
    Route::get('/painel/empresa', function () {
        return redirect()->route('transportadora.dashboard');
    })->name('company.dashboard');
});

Route::middleware(['auth', 'verified', 'profile:agenciador'])->group(function (): void {
    Route::get('/painel/agenciador', function () {
        return view('dashboards.agenciador');
    })->name('agenciador.dashboard');
});

Route::middleware(['auth', 'verified', 'profile:driver'])->group(function (): void {
    Route::get('/painel/motorista', function () {
        return view('dashboards.driver');
    })->name('driver.dashboard');
});

Route::middleware(['auth', 'verified', 'profile:driver,transportadora,agenciador,company,freightista,admin'])->group(function (): void {
    Route::view('/chat', 'chat-page')->name('chat.index');
    Route::view('/suporte', 'support-page')->name('support.index');
});

Route::middleware(['auth', 'verified', 'profile:transportadora,agenciador,company,freightista'])->group(function (): void {
    Route::get('/fretes/novo', \App\Livewire\PostFreight::class)->name('freights.create');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
