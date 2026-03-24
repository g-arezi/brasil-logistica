<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use App\Domains\Freight\Pipelines\FreightFilterPipeline;
use App\Domains\Vehicle\Enums\VehicleType;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

final class FreightBoard extends Component
{
    use WithPagination;

    public ?float $originLat = null;
    public ?float $originLng = null;
    public int $radiusKm = 50;
    public ?int $minPriceCents = null;
    public ?int $maxPriceCents = null;

    /**
     * @var list<string>
     */
    public array $vehicleTypes = [];

    #[On('freight-published')]
    public function refreshBoard(): void
    {
        $this->resetPage();
    }

    public function render(FreightFilterPipeline $pipeline): View
    {
        $filters = new FreightFilterData(
            originLat: $this->originLat,
            originLng: $this->originLng,
            radiusKm: $this->radiusKm,
            minPriceCents: $this->minPriceCents,
            maxPriceCents: $this->maxPriceCents,
            vehicleTypes: array_values(array_filter(array_map(
                static fn (string $type): ?VehicleType => VehicleType::tryFrom($type),
                $this->vehicleTypes
            ))),
        );

        return view('livewire.freight-board', [
            'freights' => $pipeline->apply($filters)->latest()->paginate(10),
            'vehicleOptions' => VehicleType::cases(),
        ]);
    }
}

