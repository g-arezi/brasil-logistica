<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Freight\DataTransferObjects\FreightFilterData;
use App\Domains\Freight\Models\Freight;
use App\Domains\Freight\Pipelines\FreightFilterPipeline;
use App\Domains\Vehicle\Enums\VehicleType;
use App\Support\BrazilLocations;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class FreightBoard extends Component
{
    use WithPagination;

    public ?string $origin_state = null;
    public ?string $origin_city = null;
    public ?string $destination_state = null;
    public ?string $destination_city = null;

    public ?Freight $selectedFreight = null;
    public bool $showingDetails = false;

    public bool $showingEdit = false;
    public ?Freight $editingFreight = null;
    public string $edit_origin_city = '';
    public string $edit_origin_state = '';
    public string $edit_destination_city = '';
    public string $edit_destination_state = '';
    public float $edit_price = 0.0;
    public string $edit_required_vehicle_type = '';
    public string $edit_details = '';

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

    public function startEdit(string $freightId): void
    {
        $freight = Freight::find($freightId);

        if ($freight) {
            $user = auth()->user();
            $isAdmin = $user?->profile_type?->value === 'admin';

            if ($freight->company_id === auth()->id() || $isAdmin) {
                $this->editingFreight = $freight;
                $this->edit_origin_city = $freight->origin_city;
                $this->edit_origin_state = $freight->origin_state;
                $this->edit_destination_city = $freight->destination_city;
                $this->edit_destination_state = $freight->destination_state;
                $this->edit_price = $freight->price_cents / 100;
                $this->edit_required_vehicle_type = $freight->required_vehicle_type->value;
                $this->edit_details = $freight->details ?? '';
                $this->showingEdit = true;
            } else {
                session()->flash('error', 'Sem permissao para editar este frete.');
            }
        }
    }

    public function saveEdit(): void
    {
        if ($this->editingFreight) {
            $this->validate([
                'edit_origin_city' => 'required|string|max:255',
                'edit_origin_state' => 'required|string|size:2',
                'edit_destination_city' => 'required|string|max:255',
                'edit_destination_state' => 'required|string|size:2',
                'edit_price' => 'required|numeric|min:1',
                'edit_required_vehicle_type' => 'required|string',
                'edit_details' => 'nullable|string',
            ]);

            $this->editingFreight->update([
                'origin_city' => $this->edit_origin_city,
                'origin_state' => strtoupper($this->edit_origin_state),
                'destination_city' => $this->edit_destination_city,
                'destination_state' => strtoupper($this->edit_destination_state),
                'price_cents' => (int) ($this->edit_price * 100),
                'required_vehicle_type' => VehicleType::from($this->edit_required_vehicle_type),
                'details' => $this->edit_details,
            ]);

            session()->flash('success', 'Frete atualizado com sucesso!');
            $this->closeEdit();
        }
    }

    public function closeEdit(): void
    {
        $this->showingEdit = false;
        $this->editingFreight = null;
    }

    public function deleteFreight(string $freightId): void
    {
        $freight = Freight::find($freightId);

        if ($freight) {
            $user = auth()->user();
            $isAdmin = $user?->profile_type?->value === 'admin';

            if ($freight->company_id === $authId = auth()->id() || $isAdmin) {
                $freight->delete();
                session()->flash('success', 'Frete excluido com sucesso!');
                if ($this->selectedFreight && $this->selectedFreight->id === $freightId) {
                    $this->closeDetails();
                }
            } else {
                session()->flash('error', 'Voce nao tem permissao para excluir este frete.');
            }
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
