<?php

declare(strict_types=1);

namespace App\Domains\Freight\DataTransferObjects;

use App\Domains\Vehicle\Enums\VehicleType;
use Spatie\LaravelData\Data;

final class FreightFilterData extends Data
{
    /**
     * @param list<VehicleType> $vehicleTypes
     */
    public function __construct(
        public readonly ?float $originLat,
        public readonly ?float $originLng,
        public readonly int $radiusKm,
        public readonly ?int $minPriceCents,
        public readonly ?int $maxPriceCents,
        public readonly array $vehicleTypes = [],
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromRequestPayload(array $payload): self
    {
        $types = array_values(array_filter(array_map(
            static fn (string $value): ?VehicleType => VehicleType::tryFrom($value),
            (array) ($payload['vehicle_types'] ?? [])
        )));

        return new self(
            originLat: isset($payload['origin_lat']) ? (float) $payload['origin_lat'] : null,
            originLng: isset($payload['origin_lng']) ? (float) $payload['origin_lng'] : null,
            radiusKm: (int) ($payload['radius_km'] ?? 50),
            minPriceCents: isset($payload['min_price_cents']) ? (int) $payload['min_price_cents'] : null,
            maxPriceCents: isset($payload['max_price_cents']) ? (int) $payload['max_price_cents'] : null,
            vehicleTypes: $types,
        );
    }
}

