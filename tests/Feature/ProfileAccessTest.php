<?php

declare(strict_types=1);

use App\Models\User;

it('redirects transportadora user to transportadora dashboard', function (): void {
    $transportadora = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '11222333000181',
    ]);

    $response = $this->actingAs($transportadora)->get('/dashboard');

    $response->assertRedirect(route('transportadora.dashboard', absolute: false));
});

it('redirects driver user to driver dashboard', function (): void {
    $driver = User::factory()->create([
        'profile_type' => 'driver',
        'document_number' => '11122233344',
    ]);

    $response = $this->actingAs($driver)->get('/dashboard');

    $response->assertRedirect(route('driver.dashboard', absolute: false));
});

it('blocks driver route for transportadora user', function (): void {
    $transportadora = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '99888777000166',
    ]);

    $response = $this->actingAs($transportadora)->get('/painel/motorista');

    $response->assertForbidden();
});

it('redirects agenciador user to agenciador dashboard', function (): void {
    $agenciador = User::factory()->create([
        'profile_type' => 'agenciador',
        'document_number' => '55666777000188',
    ]);

    $response = $this->actingAs($agenciador)->get('/dashboard');

    $response->assertRedirect(route('agenciador.dashboard', absolute: false));
});

it('redirects admin user to admin dashboard', function (): void {
    $admin = User::factory()->create([
        'profile_type' => 'admin',
        'document_number' => '12345678901',
    ]);

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertRedirect(route('admin.dashboard', absolute: false));
});
