<?php

declare(strict_types=1);

namespace App\Domains\Freight\Http\Controllers\Api;

use App\Domains\Freight\Actions\CreateFreightAction;
use App\Domains\Freight\DataTransferObjects\CreateFreightData;
use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use App\Domains\Freight\Http\Resources\FreightResource;
use App\Domains\Freight\Pipelines\FreightFilterPipeline;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class FreightController extends Controller
{
    public function index(Request $request, FreightFilterPipeline $pipeline): JsonResponse
    {
        $filters = FreightFilterData::fromRequestPayload($request->all());

        $freights = $pipeline
            ->apply($filters)
            ->latest()
            ->paginate((int) $request->integer('per_page', 15));

        return FreightResource::collection($freights)->response();
    }

    public function store(Request $request, CreateFreightAction $action): JsonResponse
    {
        $data = CreateFreightData::fromRequestPayload([
            'companyId' => (int) $request->input('company_id'),
            'originCity' => (string) $request->input('origin_city'),
            'originState' => (string) $request->input('origin_state'),
            'originLat' => (float) $request->input('origin_lat'),
            'originLng' => (float) $request->input('origin_lng'),
            'destinationCity' => (string) $request->input('destination_city'),
            'destinationState' => (string) $request->input('destination_state'),
            'destinationLat' => (float) $request->input('destination_lat'),
            'destinationLng' => (float) $request->input('destination_lng'),
            'priceCents' => (int) $request->input('price_cents'),
            'minPriceCents' => (int) $request->input('min_price_cents', $request->input('price_cents')),
            'maxPriceCents' => (int) $request->input('max_price_cents', $request->input('price_cents')),
            'requiredVehicleType' => (string) $request->input('required_vehicle_type'),
        ]);

        $freight = $action->execute($data);

        return (new FreightResource($freight))
            ->response()
            ->setStatusCode(201);
    }
}

