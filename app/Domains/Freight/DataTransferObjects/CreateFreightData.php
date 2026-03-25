<?php

declare(strict_types=1);

namespace App\Domains\Freight\DataTransferObjects;

use App\Domains\Vehicle\Enums\VehicleType;
use Spatie\LaravelData\Attributes\Validation\Between;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;

final class CreateFreightData extends Data
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromRequestPayload(array $payload): self
    {
        return self::validateAndCreate($payload);
    }

    public function __construct(
        public readonly int $companyId,
        public readonly string $originCity,
        public readonly string $originState,
        #[Numeric, Between(-90, 90)]
        public readonly float $originLat,
        #[Numeric, Between(-180, 180)]
        public readonly float $originLng,
        public readonly string $destinationCity,
        public readonly string $destinationState,
        #[Numeric, Between(-90, 90)]
        public readonly float $destinationLat,
        #[Numeric, Between(-180, 180)]
        public readonly float $destinationLng,
        #[IntegerType]
        public readonly int $priceCents,
        #[IntegerType]
        public readonly int $minPriceCents,
        #[IntegerType]
        public readonly int $maxPriceCents,
        #[In(VehicleType::class)]
        public readonly VehicleType $requiredVehicleType,
    ) {}
}
