<?php

declare(strict_types=1);

namespace App\Domains\Freight\Pipelines\Filters;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use Closure;
use Illuminate\Database\Eloquent\Builder;

final class RadiusFilter
{
    public function __construct(private readonly FreightFilterData $filters) {}

    /**
     * @param  Closure(Builder): Builder  $next
     */
    public function handle(Builder $builder, Closure $next): Builder
    {
        if ($this->filters->originLat !== null && $this->filters->originLng !== null) {
            $builder->withinRadius($this->filters->originLat, $this->filters->originLng, $this->filters->radiusKm);
        }

        return $next($builder);
    }
}
