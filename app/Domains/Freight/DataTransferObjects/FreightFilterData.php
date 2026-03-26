<?php

declare(strict_types=1);

namespace App\Domains\Freight\DataTransferObjects;

use App\Domains\Vehicle\Enums\VehicleType;
use Spatie\LaravelData\Data;

final class FreightFilterData extends Data
{
    /**
     * @param  list<VehicleType>  $vehicleTypes
     */
    public function __construct(
        public readonly ?float $originLat,
        public readonly ?float $originLng,
        public readonly int $radiusKm,
        public readonly ?int $minPriceCents,
        public readonly ?int $maxPriceCents,
        public readonly ?string $originState,
        public readonly ?string $originCity,
        public readonly ?string $destinationState,
        public readonly ?string $destinationCity,
        public readonly ?string $search = null,
        public readonly array $vehicleTypes = [],
    ) {}

    /**
     * @param  array<string, mixed>  $payload
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
            originState: self::sanitizeLocationInput($payload['origin_state'] ?? null),
            originCity: self::sanitizeLocationInput($payload['origin_city'] ?? null),
            destinationState: self::sanitizeLocationInput($payload['destination_state'] ?? null),
            destinationCity: self::sanitizeLocationInput($payload['destination_city'] ?? null),
            search: $payload['search'] ?? null,
            vehicleTypes: $types,
        );
    }

    private static function sanitizeLocationInput(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
