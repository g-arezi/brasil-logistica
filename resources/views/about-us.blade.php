<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight">
            {{ __('Sobre Nós') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border border-slate-800">
                <div class="p-6 text-slate-300">
                    <h3 class="text-2xl font-bold mb-4 text-white">Sobre a Brasil Logistica</h3>
                    <p class="mb-4">
                        A Brasil Logistica nasceu a partir da necessidade de inovar o ramo de fretagens e conectar os
                        melhores serviços disponíveis para otimizar tempo e recursos.
                    </p>
                    <p class="mb-4">
                        Nossa missão e oferecer um sistema rápido, intuitivo e seguro que atenda as necessidades do
                        transporte rodoviário do Brasil. Seja para buscar uma oportunidade ideal, seja para divulgar a
                        sua marca, nos buscamos promover as devidas pontes e solucionar esse embate logistico.
                    </p>
                    <h4 class="text-xl font-bold mb-2 mt-6 text-white">Nossos Valores</h4>
                    <ul class="list-disc list-inside space-y-2 ml-4">
                        <li>Inovação constante diante de novos gargalos;</li>
                        <li>Transparencia e honestidade perante aos usuários e serviços;</li>
                        <li>Segurança com foco na preservação da sua frota e carga;</li>
                        <li>Trabalhar para manter uma plataforma fácil que sirva como auxilio;</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
