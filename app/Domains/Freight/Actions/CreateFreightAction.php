<?php

declare(strict_types=1);

namespace App\Domains\Freight\Actions;

use App\Domains\Freight\DataTransferObjects\CreateFreightData;
use App\Domains\Freight\Enums\FreightStatus;
use App\Domains\Freight\Events\FreightPublished;
use App\Domains\Freight\Models\Freight;
use App\Domains\Freight\Services\DistanceServiceInterface;
use App\Domains\User\Models\User;
use App\Domains\User\Services\DocumentValidationServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class CreateFreightAction
{
    public function __construct(
        private readonly DistanceServiceInterface $distanceService,
        private readonly DocumentValidationServiceInterface $documentValidationService,
    ) {}

    public function execute(CreateFreightData $data): Freight
    {
        /** @var User|null $company */
        $company = User::query()->find($data->companyId);

        if ($company === null) {
            throw ValidationException::withMessages(['company_id' => ['Empresa nao encontrada.']]);
        }

        $this->assertCompanyDocumentIsValid($company);

        $distance = $this->distanceService->estimate(
            $data->originLat,
            $data->originLng,
            $data->destinationLat,
            $data->destinationLng,
        );

        /** @var Freight $freight */
        $freight = DB::transaction(function () use ($data, $distance): Freight {
            $payload = [
                'company_id' => $data->companyId,
                'origin_city' => $data->originCity,
                'origin_state' => $data->originState,
                'origin_lat' => $data->originLat,
                'origin_lng' => $data->originLng,
                'destination_city' => $data->destinationCity,
                'destination_state' => $data->destinationState,
                'destination_lat' => $data->destinationLat,
                'destination_lng' => $data->destinationLng,
                'price_cents' => $data->priceCents,
                'min_price_cents' => $data->minPriceCents,
                'max_price_cents' => $data->maxPriceCents,
                'required_vehicle_type' => $data->requiredVehicleType,
                'status' => FreightStatus::Published,
                'distance_km' => $distance['distance_km'],
                'estimated_minutes' => $distance['estimated_minutes'],
            ];

            if (DB::connection()->getDriverName() === 'pgsql') {
                $payload = array_merge($payload, Freight::buildGeoPayload(
                    ['lat' => $data->originLat, 'lng' => $data->originLng],
                    ['lat' => $data->destinationLat, 'lng' => $data->destinationLng],
                ));
            }

            return Freight::query()->create($payload);
        });

        FreightPublished::dispatch($freight);

        return $freight;
    }

    private function assertCompanyDocumentIsValid(User $company): void
    {
        if ($company->document_number === null || $company->document_number === '') {
            throw ValidationException::withMessages([
                'company_id' => ['Empresa sem documento cadastrado.'],
            ]);
        }

        if ($company->document_verified_at !== null) {
            return;
        }

        if (! $this->documentValidationService->isValid($company->document_number)) {
            throw ValidationException::withMessages([
                'company_id' => ['Documento da empresa invalido ou nao verificado.'],
            ]);
        }

        $company->forceFill(['document_verified_at' => now()])->save();
    }
}
