<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Freight\Enums\FreightStatus;
use App\Domains\Freight\Models\Freight;
use App\Domains\User\Enums\UserProfileType;
use App\Domains\Vehicle\Enums\VehicleType;
use App\Models\User;
use App\Support\BrazilLocations;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeder run...');

        User::query()
            ->whereIn('document_number', [
                '11222333000181',
                '55666777000188',
                '12345678901',
                '11122233344',
                '99988877766',
                '22333444000155',
            ])
            ->whereNotIn('email', [
                'transportadora@demo.com',
                'agenciador@demo.com',
                'admin@demo.com',
                'motorista@demo.com',
                'test@example.com',
                'empresa@demo.com',
            ])
            ->delete();

        User::query()->where('email', 'empresa@demo.com')->delete();

        $empresa = User::query()->updateOrCreate([
            'email' => 'empresa@demo.com',
        ], [
            'name' => 'Empresa Demo',
            'password' => 'password',
            'profile_type' => UserProfileType::Company,
            'status' => 'pending',
            'document_number' => '11222333000100',
            'document_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        $transportadora = User::query()->updateOrCreate([
            'email' => 'transportadora@demo.com',
        ], [
            'name' => 'Transportadora Demo',
            'password' => 'password',
            'profile_type' => UserProfileType::Transportadora,
            'status' => 'pending',
            'document_number' => '11222333000181',
            'document_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        $agenciador = User::query()->updateOrCreate([
            'email' => 'agenciador@demo.com',
        ], [
            'name' => 'Agenciador Demo',
            'password' => 'password',
            'profile_type' => UserProfileType::Agenciador,
            'status' => 'pending',
            'document_number' => '55666777000188',
            'document_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        User::query()->updateOrCreate([
            'email' => 'admin@demo.com',
        ], [
            'name' => 'Administrador Demo',
            'password' => 'ASDKASKD1q23easDASD12@@!#%ç',
            'profile_type' => UserProfileType::Admin,
            'status' => 'approved',
            'document_number' => '12345678901',
            'document_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        User::query()->updateOrCreate([
            'email' => 'motorista@demo.com',
        ], [
            'name' => 'Motorista Demo',
            'password' => 'password',
            'profile_type' => UserProfileType::Driver,
            'status' => 'pending',
            'document_number' => '11122233344',
            'document_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        User::query()->updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => 'password',
            'profile_type' => UserProfileType::Driver,
            'document_number' => '99988877766',
            'document_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        Freight::query()->delete();

        $locations = BrazilLocations::all();
        $states = array_keys($locations);
        $isPgsql = DB::connection()->getDriverName() === 'pgsql';

        for ($i = 0; $i < 24; $i++) {
            $originState = $states[array_rand($states)];
            $destinationState = $states[array_rand($states)];
            $originCity = $locations[$originState][array_rand($locations[$originState])];
            $destinationCity = $locations[$destinationState][array_rand($locations[$destinationState])];

            $originLat = fake()->latitude(-30, 5);
            $originLng = fake()->longitude(-70, -35);
            $destinationLat = fake()->latitude(-30, 5);
            $destinationLng = fake()->longitude(-70, -35);
            $priceCents = fake()->numberBetween(90000, 380000);

            $payload = [
                'company_id' => fake()->randomElement([$transportadora->id, $agenciador->id, $empresa->id]),
                'origin_city' => $originCity,
                'origin_state' => $originState,
                'origin_lat' => $originLat,
                'origin_lng' => $originLng,
                'destination_city' => $destinationCity,
                'destination_state' => $destinationState,
                'destination_lat' => $destinationLat,
                'destination_lng' => $destinationLng,
                'price_cents' => $priceCents,
                'min_price_cents' => (int) floor($priceCents * 0.85),
                'max_price_cents' => (int) ceil($priceCents * 1.15),
                'required_vehicle_type' => fake()->randomElement(VehicleType::cases()),
                'status' => FreightStatus::Published,
                'distance_km' => fake()->randomFloat(2, 20, 1800),
                'estimated_minutes' => fake()->numberBetween(60, 1800),
                'details' => fake()->paragraphs(3, true),
            ];

            if ($isPgsql) {
                $payload = array_merge($payload, Freight::buildGeoPayload(
                    ['lat' => $originLat, 'lng' => $originLng],
                    ['lat' => $destinationLat, 'lng' => $destinationLng],
                ));
            }

            Freight::query()->create($payload);
        }
    }
}
