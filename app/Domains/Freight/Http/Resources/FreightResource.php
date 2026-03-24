<?php

declare(strict_types=1);

namespace App\Domains\Freight\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Domains\Freight\Models\Freight
 */
class FreightResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'origin_city' => $this->origin_city,
            'origin_state' => $this->origin_state,
            'destination_city' => $this->destination_city,
            'destination_state' => $this->destination_state,
            'origin_lat' => $this->origin_lat,
            'origin_lng' => $this->origin_lng,
            'destination_lat' => $this->destination_lat,
            'destination_lng' => $this->destination_lng,
            'price_cents' => $this->price_cents,
            'required_vehicle_type' => $this->required_vehicle_type->value,
            'distance_km' => $this->distance_km,
            'estimated_minutes' => $this->estimated_minutes,
            'created_at' => optional($this->created_at)?->toISOString(),
        ];
    }
}

