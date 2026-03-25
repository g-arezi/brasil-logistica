<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            Painel da Empresa
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-slate-900 border-slate-800 text-slate-100 p-6 shadow-sm">
                <p class="text-gray-700">
                    Bem-vindo ao painel da empresa. Aqui voce gerencia publicacao e acompanhamento de fretes.
                </p>
                <a href="{{ route('freights.board') }}" class="mt-4 inline-flex rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">
                    Ver board de fretes
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

