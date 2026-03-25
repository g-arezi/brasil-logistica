<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-100 leading-tight">
            Painel Operacional
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <livewire:ops-overview />
        </div>
    </div>
</x-app-layout>

