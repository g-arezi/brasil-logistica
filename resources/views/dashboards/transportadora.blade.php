<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-200 leading-tight">
            Painel da Transportadora
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

                <!-- Estatísticas Resumidas -->
                <div class="rounded-lg bg-slate-900 border border-slate-800 text-slate-100 p-6 shadow-sm">
                    <h3 class="text-lg font-medium text-white mb-2">Atividade</h3>
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-emerald-500/10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-emerald-400">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Seus Fretes Publicados</p>
                            <p class="text-2xl font-bold text-white">{{ DB::table('freights')->where('company_id', auth()->id())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-slate-900 border border-slate-800 text-slate-100 p-6 shadow-sm">
                <p class="text-slate-300">
                    Gerencie sua operacao de fretes, acompanhe oportunidades e publique cargas com agilidade.
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('freights.board') }}" class="inline-flex rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">
                        Abrir board de fretes
                    </a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex rounded-md bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">
                        Editar perfil
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

