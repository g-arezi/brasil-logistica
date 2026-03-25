<?php

declare(strict_types=1);

use App\Domains\Freight\Enums\FreightStatus;
use App\Domains\Freight\Models\Freight;
use App\Domains\Vehicle\Enums\VehicleType;
use App\Models\User;

it('motorista busca fretes num raio de 50km e encontra resultados compativeis', function (): void {
    $company = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '11222333000181',
        'document_verified_at' => now(),
    ]);

    Freight::factory()->create([
        'company_id' => $company->id,
        'origin_city' => 'Sao Paulo',
        'origin_state' => 'SP',
        'origin_lat' => -23.550520,
        'origin_lng' => -46.633308,
        'destination_city' => 'Campinas',
        'destination_state' => 'SP',
        'destination_lat' => -22.905560,
        'destination_lng' => -47.060830,
        'price_cents' => 150000,
        'min_price_cents' => 120000,
        'max_price_cents' => 160000,
        'required_vehicle_type' => VehicleType::Truck,
        'status' => FreightStatus::Published,
    ]);

    Freight::factory()->create([
        'company_id' => $company->id,
        'origin_city' => 'Rio de Janeiro',
        'origin_state' => 'RJ',
        'origin_lat' => -22.906847,
        'origin_lng' => -43.172897,
        'destination_city' => 'Niteroi',
        'destination_state' => 'RJ',
        'destination_lat' => -22.883237,
        'destination_lng' => -43.103401,
        'price_cents' => 190000,
        'min_price_cents' => 180000,
        'max_price_cents' => 210000,
        'required_vehicle_type' => VehicleType::Bitrem,
        'status' => FreightStatus::Published,
    ]);

    $response = $this->getJson('/api/v1/freights?origin_lat=-23.56&origin_lng=-46.64&radius_km=50&vehicle_types[]=truck');

    $response
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.origin_city', 'Sao Paulo')
        ->assertJsonPath('data.0.required_vehicle_type', 'truck');
});

it('filtra fretes por estado e cidade de origem e destino', function (): void {
    $company = User::factory()->create([
        'profile_type' => 'transportadora',
        'document_number' => '33111222000177',
        'document_verified_at' => now(),
    ]);

    Freight::factory()->create([
        'company_id' => $company->id,
        'origin_city' => 'Sao Paulo',
        'origin_state' => 'SP',
        'origin_lat' => -23.550520,
        'origin_lng' => -46.633308,
        'destination_city' => 'Curitiba',
        'destination_state' => 'PR',
        'destination_lat' => -25.428954,
        'destination_lng' => -49.267137,
        'price_cents' => 230000,
        'min_price_cents' => 200000,
        'max_price_cents' => 250000,
        'required_vehicle_type' => VehicleType::Truck,
        'status' => FreightStatus::Published,
    ]);

    Freight::factory()->create([
        'company_id' => $company->id,
        'origin_city' => 'Rio de Janeiro',
        'origin_state' => 'RJ',
        'origin_lat' => -22.906847,
        'origin_lng' => -43.172897,
        'destination_city' => 'Salvador',
        'destination_state' => 'BA',
        'destination_lat' => -12.977749,
        'destination_lng' => -38.501630,
        'price_cents' => 260000,
        'min_price_cents' => 240000,
        'max_price_cents' => 280000,
        'required_vehicle_type' => VehicleType::Van,
        'status' => FreightStatus::Published,
    ]);

    $response = $this->getJson('/api/v1/freights?origin_state=SP&origin_city=Sao%20Paulo&destination_state=PR&destination_city=Curitiba');

    $response
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.origin_state', 'SP')
        ->assertJsonPath('data.0.destination_state', 'PR');
});

