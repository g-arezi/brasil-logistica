<?php
namespace App\Livewire;
use App\Domains\Freight\Models\Freight;
use App\Domains\Vehicle\Enums\VehicleType;
use App\Domains\Freight\Enums\FreightStatus;
use Livewire\Component;
class PostFreight extends Component
{
    public string $origin_city = '';
    public string $origin_state = '';
    public string $destination_city = '';
    public string $destination_state = '';
    public string $required_vehicle_type = '';
    public float $price = 0.0;
    public string $details = '';
    protected function rules()
    {
        return [
            'origin_city' => 'required|string|max:255',
            'origin_state' => 'required|string|size:2',
            'destination_city' => 'required|string|max:255',
            'destination_state' => 'required|string|size:2',
            'required_vehicle_type' => 'required|string|in:' . implode(',', array_column(VehicleType::cases(), 'value')),
            'price' => 'required|numeric|min:1',
            'details' => 'nullable|string',
        ];
    }
    public function save()
    {
        $this->validate();

        $payload = [
            'company_id' => auth()->id(),
            'origin_city' => $this->origin_city,
            'origin_state' => strtoupper($this->origin_state),
            'origin_lat' => 0.0, // Mock for now
            'origin_lng' => 0.0, // Mock
            'destination_city' => $this->destination_city,
            'destination_state' => strtoupper($this->destination_state),
            'destination_lat' => 0.0, // Mock
            'destination_lng' => 0.0, // Mock
            'price_cents' => (int) ($this->price * 100),
            'min_price_cents' => (int) ($this->price * 100),
            'max_price_cents' => (int) ($this->price * 100),
            'required_vehicle_type' => VehicleType::from($this->required_vehicle_type),
            'status' => FreightStatus::Published,
            'distance_km' => 0,
            'estimated_minutes' => 0,
            'details' => $this->details,
        ];

        if (config('database.default') === 'pgsql') {
            $payload = array_merge($payload, Freight::buildGeoPayload(
                ['lat' => 0.0, 'lng' => 0.0],
                ['lat' => 0.0, 'lng' => 0.0]
            ));
        }

        Freight::create($payload);

        session()->flash('success', 'Frete publicado com sucesso!');
        return redirect()->route('freights.board');
    }
    public function render()
    {
        return view('livewire.post-freight', [
            'vehicleTypes' => VehicleType::cases(),
        ])->layout('layouts.app');
    }
}
