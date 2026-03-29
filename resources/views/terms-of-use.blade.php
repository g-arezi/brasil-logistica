<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight">
            {{ __('Termos de Uso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border border-slate-800">
                <div class="p-6 text-slate-300">
                    <h3 class="text-2xl font-bold mb-4 text-white">Termos e Condicoes de Uso</h3>
                    <p class="mb-4">Bem-vindo a plataforma Brasil Logistica.</p>
                    <p class="mb-4">
                        Ao utilizar nossos servicos, voce concorda com as diretrizes e regras aqui dispostas.
                        A nossa plataforma serve como uma ponte de aproximacao para transportadoras, motoristas
                        e agenciadores visando o melhor desempenho no servico de logistica.
                    </p>
                    <p class="mb-4">
                        Voce se compromete a fornecer informacoes verdadeiras e assumir a responsabilidade pelas operacoes
                        realizadas pela sua conta, que pessoal e intransferivel, mantendo em sigilo as suas devidas credenciais.
                    </p>
                    <h4 class="text-xl font-bold mb-2 mt-6 text-white">Privacidade e Protecao a Dados</h4>
                    <p class="mb-4">
                        Seus dados estao seguros em nossos sistemas. Solicitamos apenas a informacao estritamente
                        necessaria para garantir sua operacao e nao a compartilhamos com terceiros desnecessarios,
                        seguindo perfeitamente as normas vigentes.
                    </p>
                    <p class="mt-8 text-sm text-slate-500">
                        Ultima atualizacao nestes termos foi efetuada em {{ date('d/m/Y') }}.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
