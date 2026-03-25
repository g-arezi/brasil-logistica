<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            Painel do Agenciador
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-slate-900 border-slate-800 text-slate-100 p-6 shadow-sm">
                <p class="text-gray-700">
                    Coordene negociacoes entre ofertantes e motoristas com uma visao clara de origem, destino e valores.
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('freights.board') }}" class="inline-flex rounded-md bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-500">
                        Visualizar fretes
                    </a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex rounded-md bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">
                        Editar perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

