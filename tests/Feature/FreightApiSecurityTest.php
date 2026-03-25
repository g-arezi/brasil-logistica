<?php

declare(strict_types=1);

use App\Domains\Freight\Models\Freight;
use App\Models\User;

function freightPayload(array $overrides = []): array
{
    return array_merge([
        'company_id' => 999999,
        'origin_city' => 'Sao Paulo',
        'origin_state' => 'SP',
        'origin_lat' => -23.5505,
        'origin_lng' => -46.6333,
        'destination_city' => 'Curitiba',
        'destination_state' => 'PR',
        'destination_lat' => -25.4290,
        'destination_lng' => -49.2671,
        'price_cents' => 120000,
        'min_price_cents' => 110000,
        'max_price_cents' => 130000,
        'required_vehicle_type' => 'truck',
    ], $overrides);
}

it('requires authentication to publish freights in api', function (): void {
    $this->postJson('/api/v1/freights', freightPayload())
        ->assertUnauthorized();
});

it('blocks driver profile from publishing freights in api', function (): void {
    $driver = User::factory()->create([
        'profile_type' => 'driver',
        'document_number' => '12345678900',
        'document_verified_at' => now(),
    ]);

    $this->actingAs($driver)
        ->postJson('/api/v1/freights', freightPayload())
        ->assertForbidden();
});

it('uses authenticated publisher as company and ignores forged company_id', function (): void {
    $publisher = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '12345678000199',
        'document_verified_at' => now(),
    ]);

    $otherCompany = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '98765432000188',
        'document_verified_at' => now(),
    ]);

    $response = $this->actingAs($publisher)
        ->postJson('/api/v1/freights', freightPayload([
            'company_id' => $otherCompany->id,
        ]));

    $response->assertCreated();

    $createdFreight = Freight::query()->latest('created_at')->first();

    expect($createdFreight)->not()->toBeNull();
    expect($createdFreight->company_id)->toBe($publisher->id);
});

it('requires company_id when admin publishes freight', function (): void {
    $admin = User::factory()->create([
        'profile_type' => 'admin',
        'document_number' => '11122233344',
        'document_verified_at' => now(),
    ]);

    $payload = freightPayload();
    unset($payload['company_id']);

    $this->actingAs($admin)
        ->postJson('/api/v1/freights', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['company_id']);
});

it('allows admin to publish freight for provided company_id', function (): void {
    $admin = User::factory()->create([
        'profile_type' => 'admin',
        'document_number' => '11122233344',
        'document_verified_at' => now(),
    ]);

    $company = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '12345678000199',
        'document_verified_at' => now(),
    ]);

    $response = $this->actingAs($admin)
        ->postJson('/api/v1/freights', freightPayload([
            'company_id' => $company->id,
        ]));

    $response->assertCreated();

    $createdFreight = Freight::query()->latest('created_at')->first();

    expect($createdFreight)->not()->toBeNull();
    expect($createdFreight->company_id)->toBe($company->id);
});

it('validates freight payload constraints', function (): void {
    $publisher = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '12345678000199',
        'document_verified_at' => now(),
    ]);

    $this->actingAs($publisher)
        ->postJson('/api/v1/freights', freightPayload([
            'required_vehicle_type' => 'invalid-type',
            'min_price_cents' => 130000,
            'max_price_cents' => 100000,
            'origin_lat' => 999,
        ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'required_vehicle_type',
            'min_price_cents',
            'max_price_cents',
            'origin_lat',
        ]);
});
