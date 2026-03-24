<?php

declare(strict_types=1);

namespace App\Domains\Freight\Pipelines\Filters;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use Closure;
use Illuminate\Database\Eloquent\Builder;

final class PriceRangeFilter
{
    public function __construct(private readonly FreightFilterData $filters)
    {
    }

    /**
     * @param Closure(Builder): Builder $next
     */
    public function handle(Builder $builder, Closure $next): Builder
    {
        if ($this->filters->minPriceCents !== null) {
            $builder->where('price_cents', '>=', $this->filters->minPriceCents);
        }

        if ($this->filters->maxPriceCents !== null) {
            $builder->where('price_cents', '<=', $this->filters->maxPriceCents);
        }

        return $next($builder);
    }
}

