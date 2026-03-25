<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Freight\Enums\FreightStatus;
use App\Domains\Freight\Models\Freight;
use App\Domains\Vehicle\Enums\VehicleType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Freight>
 */
class FreightFactory extends Factory
{
    protected $model = Freight::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $originLat = fake()->latitude(-30, 5);
        $originLng = fake()->longitude(-70, -35);
        $destinationLat = fake()->latitude(-30, 5);
        $destinationLng = fake()->longitude(-70, -35);
        $priceCents = fake()->numberBetween(80000, 350000);

        $payload = [
            'company_id' => User::factory(),
            'origin_city' => fake()->city(),
            'origin_state' => strtoupper(fake()->lexify('??')),
            'origin_lat' => $originLat,
            'origin_lng' => $originLng,
            'destination_city' => fake()->city(),
            'destination_state' => strtoupper(fake()->lexify('??')),
            'destination_lat' => $destinationLat,
            'destination_lng' => $destinationLng,
            'price_cents' => $priceCents,
            'min_price_cents' => (int) floor($priceCents * 0.85),
            'max_price_cents' => (int) ceil($priceCents * 1.15),
            'required_vehicle_type' => fake()->randomElement(VehicleType::cases()),
            'status' => FreightStatus::Published,
            'distance_km' => fake()->randomFloat(2, 10, 1200),
            'estimated_minutes' => fake()->numberBetween(30, 1200),
            'details' => fake()->realText(150),
        ];

        $isPgsql = DB::connection()->getDriverName() === 'pgsql';
        if ($isPgsql) {
            $geo = Freight::buildGeoPayload(
                ['lat' => $originLat, 'lng' => $originLng],
                ['lat' => $destinationLat, 'lng' => $destinationLng],
            );
            $payload = array_merge($payload, $geo);
        }

        return $payload;
    }
}
