<?php

declare(strict_types=1);

namespace App\Domains\Freight\Http\Controllers\Api;

use App\Domains\Freight\Actions\CreateFreightAction;
use App\Domains\Freight\DataTransferObjects\CreateFreightData;
use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use App\Domains\Freight\Http\Resources\FreightResource;
use App\Domains\Freight\Pipelines\FreightFilterPipeline;
use App\Domains\User\Enums\UserProfileType;
use App\Domains\Vehicle\Enums\VehicleType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class FreightController extends Controller
{
    public function index(Request $request, FreightFilterPipeline $pipeline): JsonResponse
    {
        $validated = $request->validate([
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'origin_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'origin_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'radius_km' => ['sometimes', 'integer', 'min:1', 'max:1000'],
            'min_price_cents' => ['nullable', 'integer', 'min:0'],
            'max_price_cents' => ['nullable', 'integer', 'min:0', 'gte:min_price_cents'],
            'origin_state' => ['nullable', 'string', 'size:2'],
            'origin_city' => ['nullable', 'string', 'max:120'],
            'destination_state' => ['nullable', 'string', 'size:2'],
            'destination_city' => ['nullable', 'string', 'max:120'],
            'vehicle_types' => ['sometimes', 'array', 'max:10'],
            'vehicle_types.*' => ['string', Rule::in(array_map(static fn (VehicleType $type): string => $type->value, VehicleType::cases()))],
        ]);

        $filters = FreightFilterData::fromRequestPayload($validated);

        $freights = $pipeline
            ->apply($filters)
            ->latest()
            ->paginate((int) $request->integer('per_page', 15));

        return FreightResource::collection($freights)->response();
    }

    public function store(Request $request, CreateFreightAction $action): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user === null) {
            abort(401);
        }

        $isAdmin = $user->profile_type === UserProfileType::Admin;
        $allowedPublisherProfiles = [
            UserProfileType::Transportadora,
            UserProfileType::Agenciador,
            UserProfileType::Company,
            UserProfileType::FreightistaLegacy,
        ];

        if (! $isAdmin && ! in_array($user->profile_type, $allowedPublisherProfiles, true)) {
            abort(403, 'Perfil sem permissao para publicar frete.');
        }

        $validated = $request->validate([
            'company_id' => $isAdmin ? ['required', 'integer', 'exists:users,id'] : ['nullable', 'integer'],
            'origin_city' => ['required', 'string', 'max:120'],
            'origin_state' => ['required', 'string', 'size:2'],
            'origin_lat' => ['required', 'numeric', 'between:-90,90'],
            'origin_lng' => ['required', 'numeric', 'between:-180,180'],
            'destination_city' => ['required', 'string', 'max:120'],
            'destination_state' => ['required', 'string', 'size:2'],
            'destination_lat' => ['required', 'numeric', 'between:-90,90'],
            'destination_lng' => ['required', 'numeric', 'between:-180,180'],
            'price_cents' => ['required', 'integer', 'min:1'],
            'min_price_cents' => ['nullable', 'integer', 'min:1', 'lte:price_cents'],
            'max_price_cents' => ['nullable', 'integer', 'min:1', 'gte:price_cents'],
            'required_vehicle_type' => ['required', 'string', Rule::in(array_map(static fn (VehicleType $type): string => $type->value, VehicleType::cases()))],
        ]);

        $companyId = $isAdmin
            ? (int) ($validated['company_id'] ?? 0)
            : $user->id;

        if ($companyId <= 0) {
            throw ValidationException::withMessages([
                'company_id' => ['company_id obrigatorio para administrador.'],
            ]);
        }

        $data = new CreateFreightData(
            companyId: $companyId,
            originCity: (string) $validated['origin_city'],
            originState: strtoupper((string) $validated['origin_state']),
            originLat: (float) $validated['origin_lat'],
            originLng: (float) $validated['origin_lng'],
            destinationCity: (string) $validated['destination_city'],
            destinationState: strtoupper((string) $validated['destination_state']),
            destinationLat: (float) $validated['destination_lat'],
            destinationLng: (float) $validated['destination_lng'],
            priceCents: (int) $validated['price_cents'],
            minPriceCents: (int) ($validated['min_price_cents'] ?? $validated['price_cents']),
            maxPriceCents: (int) ($validated['max_price_cents'] ?? $validated['price_cents']),
            requiredVehicleType: VehicleType::from((string) $validated['required_vehicle_type']),
        );

        $freight = $action->execute($data);

        return (new FreightResource($freight))
            ->response()
            ->setStatusCode(201);
    }
}
