<?php

declare(strict_types=1);

use App\Models\User;

it('redirects company user to company dashboard', function (): void {
    $company = User::factory()->create([
        'profile_type' => 'company',
        'document_number' => '11222333000181',
    ]);

    $response = $this->actingAs($company)->get('/dashboard');

    $response->assertRedirect(route('company.dashboard', absolute: false));
});

it('redirects driver user to driver dashboard', function (): void {
    $driver = User::factory()->create([
        'profile_type' => 'driver',
        'document_number' => '11122233344',
    ]);

    $response = $this->actingAs($driver)->get('/dashboard');

    $response->assertRedirect(route('driver.dashboard', absolute: false));
});

it('blocks driver route for company user', function (): void {
    $company = User::factory()->create([
        'profile_type' => 'company',
        'document_number' => '99888777000166',
    ]);

    $response = $this->actingAs($company)->get('/painel/motorista');

    $response->assertForbidden();
});

