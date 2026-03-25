<?php

declare(strict_types=1);

namespace App\Domains\Freight\Pipelines\Filters;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use Closure;
use Illuminate\Database\Eloquent\Builder;

final class VehicleTypeFilter
{
    public function __construct(private readonly FreightFilterData $filters) {}

    /**
     * @param  Closure(Builder): Builder  $next
     */
    public function handle(Builder $builder, Closure $next): Builder
    {
        if ($this->filters->vehicleTypes !== []) {
            $builder->whereIn(
                'required_vehicle_type',
                array_map(static fn ($type): string => $type->value, $this->filters->vehicleTypes)
            );
        }

        return $next($builder);
    }
}
