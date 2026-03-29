<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            Painel do Motorista
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informações do Plano -->
                <div class="rounded-lg bg-slate-900 border border-slate-800 text-slate-100 p-6 shadow-sm">
                    <h3 class="text-lg font-medium text-white mb-2">Seu Plano</h3>
                    @if(auth()->user()->subscription_expires_at)
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-indigo-500/10 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-400">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-400">Dias Restantes</p>
                                <p class="text-2xl font-bold text-white">{{ max(0, (int) ceil(now()->floatDiffInDays(auth()->user()->subscription_expires_at, false))) }} dias</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mt-4">Expira em: {{ auth()->user()->subscription_expires_at->format('d/m/Y') }}</p>
                    @else
                        <p class="text-sm text-slate-400">Plano vitalício ativo.</p>
                    @endif
                </div>
            </div>

            <div class="rounded-lg bg-slate-900 border border-slate-800 text-slate-100 p-6 shadow-sm">
                <p class="text-slate-300">
                    Bem-vindo ao painel do motorista. Use os filtros para encontrar fretes proximos e compativeis.
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('freights.board') }}" class="inline-flex rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">
                        Ir para board de fretes
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

