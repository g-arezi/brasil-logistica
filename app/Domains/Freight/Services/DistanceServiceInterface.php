<?php

declare(strict_types=1);

namespace App\Domains\Freight\Services;

interface DistanceServiceInterface
{
    /**
     * @return array{distance_km:float,estimated_minutes:int}
     */
    public function estimate(float $originLat, float $originLng, float $destinationLat, float $destinationLng): array;
}

