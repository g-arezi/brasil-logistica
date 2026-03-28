<?php

declare(strict_types=1);

namespace App\Domains\Freight\Pipelines;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use App\Domains\Freight\Models\Freight;
use App\Domains\Freight\Pipelines\Filters\DestinationLocationFilter;
use App\Domains\Freight\Pipelines\Filters\OriginLocationFilter;
use App\Domains\Freight\Pipelines\Filters\PriceRangeFilter;
use App\Domains\Freight\Pipelines\Filters\RadiusFilter;
use App\Domains\Freight\Pipelines\Filters\SearchFilter;
use App\Domains\Freight\Pipelines\Filters\VehicleTypeFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

final class FreightFilterPipeline
{
    public function __construct(private readonly Pipeline $pipeline) {}

    public function apply(FreightFilterData $filters): Builder
    {
        $builder = Freight::query()
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });

        /** @var Builder $result */
        $result = $this->pipeline
            ->send($builder)
            ->through([
                new SearchFilter($filters),
                new RadiusFilter($filters),
                new OriginLocationFilter($filters),
                new DestinationLocationFilter($filters),
                new VehicleTypeFilter($filters),
                new PriceRangeFilter($filters),
            ])
            ->thenReturn();

        return $result;
    }
}
