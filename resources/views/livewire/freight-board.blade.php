<div class="mx-auto max-w-6xl space-y-4 p-6">
    <div class="rounded-lg bg-white p-4 shadow">
        <h1 class="mb-4 text-xl font-semibold text-gray-900">Fretes Disponiveis</h1>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
            <input wire:model.live="originLat" type="number" step="0.000001" placeholder="Latitude" class="rounded border-gray-300" />
            <input wire:model.live="originLng" type="number" step="0.000001" placeholder="Longitude" class="rounded border-gray-300" />
            <input wire:model.live="radiusKm" type="number" min="1" placeholder="Raio (km)" class="rounded border-gray-300" />
            <input wire:model.live="minPriceCents" type="number" min="0" placeholder="Preco minimo (centavos)" class="rounded border-gray-300" />
            <input wire:model.live="maxPriceCents" type="number" min="0" placeholder="Preco maximo (centavos)" class="rounded border-gray-300" />

            <select wire:model.live="vehicleTypes" multiple class="rounded border-gray-300 md:col-span-3">
                @foreach ($vehicleOptions as $option)
                    <option value="{{ $option->value }}">{{ strtoupper($option->value) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Origem</th>
                    <th class="px-4 py-2 text-left">Destino</th>
                    <th class="px-4 py-2 text-left">Tipo</th>
                    <th class="px-4 py-2 text-left">Preco</th>
                    <th class="px-4 py-2 text-left">Distancia</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($freights as $freight)
                    <tr>
                        <td class="px-4 py-2">{{ $freight->origin_city }}/{{ $freight->origin_state }}</td>
                        <td class="px-4 py-2">{{ $freight->destination_city }}/{{ $freight->destination_state }}</td>
                        <td class="px-4 py-2">{{ strtoupper($freight->required_vehicle_type->value) }}</td>
                        <td class="px-4 py-2">R$ {{ number_format($freight->price_cents / 100, 2, ',', '.') }}</td>
                        <td class="px-4 py-2">{{ number_format((float) $freight->distance_km, 1, ',', '.') }} km</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-3 text-gray-500" colspan="5">Nenhum frete encontrado com os filtros atuais.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $freights->links() }}</div>

    <script>
        document.addEventListener('livewire:init', () => {
            window.Echo.channel('freights').listen('.FreightPublished', () => {
                Livewire.dispatch('freight-published');
            });
        });
    </script>
</div>

