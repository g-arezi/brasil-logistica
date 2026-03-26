<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            Painel Administrativo
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-slate-900 border border-slate-800 text-slate-100 p-6 shadow-sm mb-6">
                <p class="text-gray-700">
                    Area administrativa: governanca do sistema, suporte operacional e visao global da plataforma.
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('freights.board') }}" class="inline-flex rounded-md bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">
                        Ver board de fretes
                    </a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">
                        Configurar perfil
                    </a>
                </div>
            </div>

            <livewire:admin-user-management />
        </div>
    </div>
</x-app-layout>

