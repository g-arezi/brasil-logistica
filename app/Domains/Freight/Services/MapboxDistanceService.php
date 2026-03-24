<?php

declare(strict_types=1);

namespace App\Domains\Freight\Services;

use Illuminate\Support\Facades\Http;

final class MapboxDistanceService implements DistanceServiceInterface
{
    /**
     * @return array{distance_km:float,estimated_minutes:int}
     */
    public function estimate(float $originLat, float $originLng, float $destinationLat, float $destinationLng): array
    {
        $token = (string) config('services.mapbox.token', '');

        if ($token === '') {
            return $this->estimateFallback($originLat, $originLng, $destinationLat, $destinationLng);
        }

        $coordinates = sprintf('%F,%F;%F,%F', $originLng, $originLat, $destinationLng, $destinationLat);

        $response = Http::baseUrl((string) config('services.mapbox.base_url'))
            ->get("directions/v5/mapbox/driving/{$coordinates}", [
                'access_token' => $token,
                'overview' => 'false',
            ]);

        if (! $response->successful()) {
            return $this->estimateFallback($originLat, $originLng, $destinationLat, $destinationLng);
        }

        /** @var array{distance?:float,duration?:float}|null $route */
        $route = data_get($response->json(), 'routes.0');

        if ($route === null) {
            return $this->estimateFallback($originLat, $originLng, $destinationLat, $destinationLng);
        }

        return [
            'distance_km' => round(((float) ($route['distance'] ?? 0.0)) / 1000, 2),
            'estimated_minutes' => (int) round(((float) ($route['duration'] ?? 0.0)) / 60),
        ];
    }

    /**
     * @return array{distance_km:float,estimated_minutes:int}
     */
    private function estimateFallback(float $originLat, float $originLng, float $destinationLat, float $destinationLng): array
    {
        $earthRadiusKm = 6371;
        $latDelta = deg2rad($destinationLat - $originLat);
        $lngDelta = deg2rad($destinationLng - $originLng);
        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($originLat)) * cos(deg2rad($destinationLat)) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distanceKm = round($earthRadiusKm * $c, 2);

        return [
            'distance_km' => $distanceKm,
            'estimated_minutes' => (int) round(($distanceKm / 60) * 60),
        ];
    }
}

