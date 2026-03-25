<div class="mx-auto max-w-7xl space-y-6 p-6 font-sans text-slate-100">
    <section class="rounded-2xl border border-slate-800 bg-slate-900 p-6 shadow-2xl shadow-cyan-900/20">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold leading-tight">Painel de Fretes</h1>
                <p class="mt-2 text-sm text-slate-300">
                    Busca inteligente por localizacao, preco e tipo de veiculo com atualizacao em tempo real.
                </p>
                <div class="mt-4 flex gap-3">
                    <a href="{{ route('home') }}" class="inline-flex rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium hover:bg-slate-700 transition">
                        Voltar para home
                    </a>
                    @auth
                        @if (in_array(auth()->user()->profile_type?->value, ['transportadora', 'agenciador', 'company', 'freightista']))
                            <a href="{{ route('freights.create') }}" class="inline-flex items-center rounded-lg bg-cyan-600 px-4 py-2 text-sm font-medium text-white shadow-lg shadow-cyan-900/50 hover:bg-cyan-500 transition">
                                + Postar Novo Frete
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-800 bg-slate-900 p-5 shadow-2xl shadow-cyan-900/20">
        <div class="mb-5 flex items-center justify-between border-b border-slate-800 pb-4">
            <h2 class="text-xl font-semibold text-slate-100">Filtros de busca</h2>
            <button
                type="button"
                wire:click="clearFilters"
                class="rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium text-slate-200 transition hover:bg-slate-700"
            >
                Limpar filtros
            </button>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-4">
            <label class="space-y-1 text-sm">
                <span class="font-medium text-slate-300">Estado de origem</span>
                <select wire:model.live="origin_state" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">Todos</option>
                    @foreach ($states as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                </select>
            </label>

            <label class="space-y-1 text-sm">
                <span class="font-medium text-slate-300">Cidade de origem</span>
                <select wire:model.live="origin_city" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">Todas</option>
                    @foreach ($originCityOptions as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </label>

            <label class="space-y-1 text-sm">
                <span class="font-medium text-slate-300">Estado de destino</span>
                <select wire:model.live="destination_state" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">Todos</option>
                    @foreach ($states as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                </select>
            </label>

            <label class="space-y-1 text-sm">
                <span class="font-medium text-slate-300">Cidade de destino</span>
                <select wire:model.live="destination_city" class="w-full rounded-lg border-slate-700 bg-slate-950 text-slate-100 placeholder-slate-500 focus:border-cyan-500 focus:ring-cyan-500">
                    <option value="">Todas</option>
                    @foreach ($destinationCityOptions as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
            </label>

            <div class="space-y-3 text-sm md:col-span-4">
                <span class="block font-medium text-slate-300">Tipos de veiculo</span>
                <div class="flex flex-wrap gap-3">
                    @foreach ($vehicleOptions as $option)
                        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 transition hover:bg-slate-800 has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-900/30 has-[:checked]:text-cyan-300">
                            <input type="checkbox" wire:model.live="vehicle_types" value="{{ $option->value }}" class="sr-only" />
                            <span class="font-medium">{{ strtoupper($option->value) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 shadow-2xl shadow-cyan-900/20">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-950 text-xs uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-5 py-4 text-left font-medium">Postado por</th>
                        <th class="px-5 py-4 text-left font-medium">Origem</th>
                        <th class="px-5 py-4 text-left font-medium">Destino</th>
                        <th class="px-5 py-4 text-left font-medium">Tipo</th>
                        <th class="px-5 py-4 text-left font-medium">Preco</th>
                        <th class="px-5 py-4 text-left font-medium">Acesso</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 bg-slate-900">
                    @forelse ($freights as $freight)
                        <tr class="transition hover:bg-slate-800/50">
                            <td class="px-5 py-4 text-slate-300">
                                @auth
                                    <div class="font-medium">{{ $freight->company?->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-500">{{ ucfirst($freight->company?->profile_type?->value ?? 'N/A') }}</div>
                                @else
                                    <span class="text-xs text-slate-500 italic">Disponível para usuários logados</span>
                                @endauth
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-200">{{ $freight->origin_city }} / {{ $freight->origin_state }}</td>
                            <td class="px-5 py-4 text-slate-300">{{ $freight->destination_city }} / {{ $freight->destination_state }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full bg-cyan-500/10 px-3 py-1 text-xs font-semibold text-cyan-400 border border-cyan-500/20">
                                    {{ strtoupper($freight->required_vehicle_type->value) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-emerald-400">R$ {{ number_format($freight->price_cents / 100, 2, ',', '.') }}</td>
                            <td class="px-5 py-4 text-slate-300 flex items-center gap-2">
                                <button type="button" wire:click="showDetails('{{ $freight->id }}')" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-3 py-1.5 text-xs font-medium text-white hover:bg-slate-600 transition">
                                    Ver Detalhes
                                </button>
                                @auth
                                    @if ($freight->company_id === auth()->id())
                                        <button type="button"
                                            wire:click="deleteFreight('{{ $freight->id }}')"
                                            wire:confirm="Tem certeza que deseja excluir este frete?"
                                            class="inline-flex items-center gap-2 rounded-lg border border-red-500/50 bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-400 hover:bg-red-500/20 hover:text-red-300 transition">
                                            Excluir
                                        </button>
                                    @else
                                        <a href="{{ route('chat.index', ['freight_id' => $freight->id]) }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500 transition">
                                            Falar no Chat
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="text-xs text-indigo-400 hover:text-indigo-300 underline">Fazer login</a>
                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-8 text-center text-slate-500" colspan="6">
                                Nenhum frete encontrado com os filtros atuais.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
        {{ $freights->links(data: ['scrollTo' => false]) }}
    </div>

    @if($showingDetails && $selectedFreight)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-2xl rounded-2xl bg-slate-900 p-6 shadow-2xl border border-slate-700">
                <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-4">
                    <h3 class="text-xl font-bold text-slate-100">Detalhes do Frete</h3>
                    <button wire:click="closeDetails" class="text-slate-400 hover:text-slate-200 text-2xl">&times;</button>
                </div>

                <div class="space-y-4 text-sm text-slate-300">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block font-medium text-slate-400">Origem</span>
                            <span class="text-slate-100">{{ $selectedFreight->origin_city }} / {{ $selectedFreight->origin_state }}</span>
                        </div>
                        <div>
                            <span class="block font-medium text-slate-400">Destino</span>
                            <span class="text-slate-100">{{ $selectedFreight->destination_city }} / {{ $selectedFreight->destination_state }}</span>
                        </div>
                        <div>
                            <span class="block font-medium text-slate-400">Distancia Estimada</span>
                            <span class="text-slate-100">{{ number_format($selectedFreight->distance_km ?? 0, 1, ',', '.') }} km</span>
                        </div>
                        <div>
                            <span class="block font-medium text-slate-400">Tempo Estimado</span>
                            <span class="text-slate-100">
                                @if($selectedFreight->estimated_minutes)
                                    {{ floor($selectedFreight->estimated_minutes / 60) }}h {{ $selectedFreight->estimated_minutes % 60 }}m
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="block font-medium text-slate-400">Veiculo Requerido</span>
                            <span class="text-cyan-400 font-medium">{{ strtoupper($selectedFreight->required_vehicle_type->value) }}</span>
                        </div>
                        <div>
                            <span class="block font-medium text-slate-400">Preco</span>
                            <span class="text-emerald-400 font-semibold">R$ {{ number_format($selectedFreight->price_cents / 100, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-800">
                        <span class="block font-medium text-slate-400 mb-2">Informacoes Adicionais</span>
                        <div class="p-3 bg-slate-950 rounded border border-slate-800 text-slate-200 whitespace-pre-line">
                            {{ $selectedFreight->details ?: 'Nenhuma observacao ou detalhe adicional fornecido.' }}
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-800">
                    <button wire:click="closeDetails" class="px-4 py-2 rounded-lg border border-slate-700 text-slate-200 hover:bg-slate-800 transition">
                        Fechar
                    </button>
                    @auth
                        @if ($selectedFreight->company_id === auth()->id())
                            <button type="button"
                                wire:click="deleteFreight('{{ $selectedFreight->id }}'); closeDetails();"
                                wire:confirm="Tem certeza que deseja excluir este frete?"
                                class="px-4 py-2 rounded-lg bg-red-600/90 text-white font-medium hover:bg-red-600 transition">
                                Excluir Frete
                            </button>
                        @else
                            <a href="{{ route('chat.index', ['freight_id' => $selectedFreight->id]) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-500 transition">
                                Falar no Chat
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-slate-700 text-white font-medium hover:bg-slate-600 transition">
                            Fazer Login para Falar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    @endif

    @once
        <script>
            document.addEventListener('livewire:init', () => {
                if (window.__freightBoardRealtimeBound) return;
                window.__freightBoardRealtimeBound = true;
                window.Echo.channel('freights').listen('.FreightPublished', () => {
                    window.Livewire?.dispatch('freight-published');
                });
            });
        </script>
    @endonce
</div>

