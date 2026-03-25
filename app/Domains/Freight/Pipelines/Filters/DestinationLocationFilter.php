<?php

declare(strict_types=1);

namespace App\Domains\Freight\Pipelines\Filters;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use Closure;
use Illuminate\Database\Eloquent\Builder;

final class DestinationLocationFilter
{
    public function __construct(private readonly FreightFilterData $filters) {}

    /**
     * @param  Closure(Builder): Builder  $next
     */
    public function handle(Builder $builder, Closure $next): Builder
    {
        if ($this->filters->destinationState !== null) {
            $builder->where('destination_state', strtoupper($this->filters->destinationState));
        }

        if ($this->filters->destinationCity !== null) {
            $builder->where('destination_city', $this->filters->destinationCity);
        }

        return $next($builder);
    }
}
