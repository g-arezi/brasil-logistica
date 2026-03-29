<?php

namespace App\Livewire;

use App\Domains\Freight\Enums\FreightStatus;
use App\Domains\Freight\Models\Freight;
use App\Domains\Vehicle\Enums\VehicleType;
use Livewire\Component;

class PostFreight extends Component
{
    public string $origin_city = '';

    public string $origin_state = '';

    public string $destination_city = '';

    public string $destination_state = '';

    public string $required_vehicle_type = '';

    public string $other_vehicle_type = '';

    public float $price = 0.0;

    public string $details = '';

    public string $contact_phone = '';

    public string $available_days_type = '2';

    public string $other_available_days = '';

    protected function rules()
    {
        return [
            'origin_city' => ['required', 'string', 'max:255'],
            'origin_state' => ['required', 'string', 'size:2'],
            'destination_city' => ['required', 'string', 'max:255'],
            'destination_state' => ['required', 'string', 'size:2'],
            'required_vehicle_type' => ['required', 'string', 'in:'.implode(',', array_column(VehicleType::cases(), 'value'))],
            'price' => ['required', 'numeric', 'min:1'],
            'details' => ['nullable', 'string'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'other_vehicle_type' => ['nullable', 'string', 'max:255'],
            'available_days_type' => ['required', 'string', 'in:2,7,other'],
            'other_available_days' => ['nullable', 'numeric', 'min:1', 'max:30'],
        ];
    }

    public function save()
    {
        $this->validate();

        // Calculate days
        $days = 2;
        if ($this->available_days_type === '7') {
            $days = 7;
        } elseif ($this->available_days_type === 'other' && is_numeric($this->other_available_days)) {
            $days = min(30, max(1, (int) $this->other_available_days));
        }

        if ($this->required_vehicle_type === 'outros' && trim($this->other_vehicle_type) !== '') {
            $append = 'Veiculo Especifico: '.trim($this->other_vehicle_type);
            $this->details = $this->details ? $append."\n\n".$this->details : $append;
        }
        $payload = [
            'company_id' => auth()->id(),
            'origin_city' => $this->origin_city,
            'origin_state' => strtoupper($this->origin_state),
            'origin_lat' => 0.0,
            'origin_lng' => 0.0,
            'destination_city' => $this->destination_city,
            'destination_state' => strtoupper($this->destination_state),
            'destination_lat' => 0.0,
            'destination_lng' => 0.0,
            'price_cents' => (int) ($this->price * 100),
            'min_price_cents' => (int) ($this->price * 100),
            'max_price_cents' => (int) ($this->price * 100),
            'required_vehicle_type' => VehicleType::from($this->required_vehicle_type),
            'status' => FreightStatus::Published,
            'distance_km' => 0,
            'estimated_minutes' => 0,
            'details' => $this->details,
            'contact_phone' => $this->contact_phone,
            'available_days' => $days,
            'expires_at' => now()->addDays($days),
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
