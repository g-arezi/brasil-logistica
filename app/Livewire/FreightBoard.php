<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use App\Domains\Freight\Pipelines\FreightFilterPipeline;
use App\Domains\Vehicle\Enums\VehicleType;
use App\Support\BrazilLocations;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Domains\Freight\Models\Freight;

class FreightBoard extends Component
{
    use WithPagination;

    public ?string $origin_state = null;
    public ?string $origin_city = null;
    public ?string $destination_state = null;
    public ?string $destination_city = null;

    public ?Freight $selectedFreight = null;
    public bool $showingDetails = false;

    /**
     * @var list<string>
     */
    public array $vehicle_types = [];

    #[On('freight-published')]
    public function refreshBoard(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->origin_state = null;
        $this->origin_city = null;
        $this->destination_state = null;
        $this->destination_city = null;
        $this->vehicle_types = [];

        $this->resetPage();
    }

    public function updatedOriginState(): void
    {
        $this->origin_city = null;
        $this->resetPage();
    }

    public function updatedDestinationState(): void
    {
        $this->destination_city = null;
        $this->resetPage();
    }

    public function showDetails(string $freightId): void
    {
        $this->selectedFreight = Freight::find($freightId);
        $this->showingDetails = true;
    }

    public function closeDetails(): void
    {
        $this->showingDetails = false;
        $this->selectedFreight = null;
    }

    public function deleteFreight(string $freightId): void
    {
        $freight = Freight::find($freightId);

        if ($freight && $freight->company_id === auth()->id()) {
            $freight->delete();
            session()->flash('success', 'Frete excluido com sucesso!');
        } else {
            session()->flash('error', 'Voce nao tem permissao para excluir este frete.');
        }
    }

    public function render(FreightFilterPipeline $pipeline): View
    {
        $filters = new FreightFilterData(
            originLat: null,
            originLng: null,
            radiusKm: 50,
            minPriceCents: null,
            maxPriceCents: null,
            originState: $this->origin_state,
            originCity: $this->origin_city,
            destinationState: $this->destination_state,
            destinationCity: $this->destination_city,
            vehicleTypes: array_values(array_filter(array_map(
                static fn (string $type): ?VehicleType => VehicleType::tryFrom($type),
                $this->vehicle_types
            ))),
        );

        $states = BrazilLocations::states();
        $filteredQuery = $pipeline->apply($filters);

        return view('livewire.freight-board', [
            'freights' => $filteredQuery->latest()->paginate(10),
            'vehicleOptions' => VehicleType::cases(),
            'states' => $states,
            'originCityOptions' => BrazilLocations::citiesByState($this->origin_state),
            'destinationCityOptions' => BrazilLocations::citiesByState($this->destination_state),
        ]);
    }
}
