<?php

declare(strict_types=1);

namespace App\Domains\Freight\Pipelines\Filters;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use Illuminate\Database\Eloquent\Builder;

final class SearchFilter
{
    public function __construct(private readonly FreightFilterData $data) {}

    public function handle(Builder $builder, \Closure $next): Builder
    {
        if ($this->data->search !== null && $this->data->search !== '') {
            $builder->where(function (Builder $query) {
                $query->where('origin_city', 'ilike', '%'.$this->data->search.'%')
                    ->orWhere('origin_state', 'ilike', '%'.$this->data->search.'%')
                    ->orWhere('destination_city', 'ilike', '%'.$this->data->search.'%')
                    ->orWhere('destination_state', 'ilike', '%'.$this->data->search.'%')
                    ->orWhere('details', 'ilike', '%'.$this->data->search.'%')
                    ->orWhere('contact_phone', 'ilike', '%'.$this->data->search.'%');
            });
        }

        return $next($builder);
    }
}
