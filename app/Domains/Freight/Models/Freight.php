<?php

declare(strict_types=1);

namespace App\Domains\Freight\Models;

use App\Casts\MoneyCast;
use App\Domains\Freight\Enums\FreightStatus;
use App\Domains\User\Models\User;
use App\Domains\Vehicle\Enums\VehicleType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property string $id
 * @property int $company_id
 * @property string $origin_city
 * @property string $origin_state
 * @property float $origin_lat
 * @property float $origin_lng
 * @property string $destination_city
 * @property string $destination_state
 * @property float $destination_lat
 * @property float $destination_lng
 * @property int $price_cents
 * @property int $min_price_cents
 * @property int $max_price_cents
 * @property VehicleType $required_vehicle_type
 * @property FreightStatus $status
 */
class Freight extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'origin_city',
        'origin_state',
        'origin_lat',
        'origin_lng',
        'destination_city',
        'destination_state',
        'destination_lat',
        'destination_lng',
        'price_cents',
        'min_price_cents',
        'max_price_cents',
        'required_vehicle_type',
        'status',
        'origin_point',
        'destination_point',
        'distance_km',
        'estimated_minutes',
        'details',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'price_cents' => MoneyCast::class,
        'min_price_cents' => MoneyCast::class,
        'max_price_cents' => MoneyCast::class,
        'required_vehicle_type' => VehicleType::class,
        'status' => FreightStatus::class,
        'origin_lat' => 'float',
        'origin_lng' => 'float',
        'destination_lat' => 'float',
        'destination_lng' => 'float',
        'distance_km' => 'float',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\FreightFactory::new();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function scopeWithinRadius(Builder $query, float $latitude, float $longitude, int $radiusKm): Builder
    {
        $driver = $query->getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            return $query->whereRaw(
                'ST_DWithin(origin_point, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)',
                [$longitude, $latitude, $radiusKm * 1000]
            );
        }

        $latDelta = $radiusKm / 111.12;
        $lngDelta = $radiusKm / 111.12;

        return $query
            ->whereBetween('origin_lat', [$latitude - $latDelta, $latitude + $latDelta])
            ->whereBetween('origin_lng', [$longitude - $lngDelta, $longitude + $lngDelta]);
    }

    /**
     * @param array{lat:float,lng:float} $origin
     * @param array{lat:float,lng:float} $destination
     * @return array<string, mixed>
     */
    public static function buildGeoPayload(array $origin, array $destination): array
    {
        return [
            'origin_point' => DB::raw(sprintf('ST_SetSRID(ST_MakePoint(%F, %F), 4326)::geography', $origin['lng'], $origin['lat'])),
            'destination_point' => DB::raw(sprintf('ST_SetSRID(ST_MakePoint(%F, %F), 4326)::geography', $destination['lng'], $destination['lat'])),
        ];
    }
}

