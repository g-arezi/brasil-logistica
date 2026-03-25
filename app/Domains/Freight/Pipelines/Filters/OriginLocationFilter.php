<?php

declare(strict_types=1);

namespace App\Domains\Freight\Pipelines\Filters;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use Closure;
use Illuminate\Database\Eloquent\Builder;

final class OriginLocationFilter
{
    public function __construct(private readonly FreightFilterData $filters)
    {
    }

    /**
     * @param Closure(Builder): Builder $next
     */
    public function handle(Builder $builder, Closure $next): Builder
    {
        if ($this->filters->originState !== null) {
            $builder->where('origin_state', strtoupper($this->filters->originState));
        }

        if ($this->filters->originCity !== null) {
            $builder->where('origin_city', $this->filters->originCity);
        }

        return $next($builder);
    }
}

