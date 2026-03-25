<div class="mx-auto max-w-7xl space-y-6 p-6 font-sans text-slate-100">
    <section class="rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-cyan-900/20">
        <h1 class="text-3xl font-bold leading-tight">Postar Novo Frete</h1>
        <p class="mt-2 text-sm text-slate-300">
            Forneça os detalhes do frete, incluindo valores e observações adicionais.
        </p>
    </section>

    <section class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-2xl shadow-cyan-900/20">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <label class="space-y-1 text-sm">
                    <span class="font-medium text-slate-300">Cidade de Origem</span>
                    <input wire:model="origin_city" type="text" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Ex: São Paulo" required />
                    @error('origin_city') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>

                <label class="space-y-1 text-sm">
                    <span class="font-medium text-slate-300">Estado de Origem (UF)</span>
                    <input wire:model="origin_state" type="text" maxlength="2" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Ex: SP" required />
                    @error('origin_state') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>

                <label class="space-y-1 text-sm">
                    <span class="font-medium text-slate-300">Cidade de Destino</span>
                    <input wire:model="destination_city" type="text" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Ex: Rio de Janeiro" required />
                    @error('destination_city') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>

                <label class="space-y-1 text-sm">
                    <span class="font-medium text-slate-300">Estado de Destino (UF)</span>
                    <input wire:model="destination_state" type="text" maxlength="2" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Ex: RJ" required />
                    @error('destination_state') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>

                <label class="space-y-1 text-sm">
                    <span class="font-medium text-slate-300">Tipo de Veículo</span>
                    <select wire:model="required_vehicle_type" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500" required>
                        <option value="">Selecione...</option>
                        @foreach ($vehicleTypes as $type)
                            <option value="{{ $type->value }}">{{ strtoupper($type->value) }}</option>
                        @endforeach
                    </select>
                    @error('required_vehicle_type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>

                <label class="space-y-1 text-sm">
                    <span class="font-medium text-slate-300">Valor do Frete (R$)</span>
                    <input wire:model="price" type="number" step="0.01" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Ex: 1500.00" required />
                    @error('price') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>

                <label class="space-y-1 text-sm md:col-span-2">
                    <span class="font-medium text-slate-300">Detalhes e Observações Adicionais</span>
                    <textarea wire:model="details" rows="4" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Informações extras como restrições de horário, características da carga, contatos..."></textarea>
                    @error('details') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </label>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('freights.board') }}" class="rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-700">
                    Cancelar
                </a>
                <button type="submit" class="rounded-lg bg-cyan-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-cyan-500 shadow-lg shadow-cyan-900/50">
                    Publicar Frete
                </button>
            </div>
        </form>
    </section>
</div>

