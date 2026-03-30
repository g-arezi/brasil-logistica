<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight">
            {{ __('Termos de Uso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border border-slate-800">
                <div class="p-6 text-slate-300 space-y-6">
                    <h3 class="text-2xl font-bold mb-6 text-white text-center">TERMO DE USO DA PLATAFORMA</h3>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">1. ACEITAÇÃO DOS TERMOS</h4>
                        <p class="leading-relaxed">
                            Ao acessar e utilizar esta plataforma de anúncios de fretes, o usuário declara que leu, compreendeu e concorda integralmente com os presentes Termos de Uso. Caso não concorde com qualquer condição aqui estabelecida, o usuário não deverá utilizar a plataforma.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">2. OBJETO DA PLATAFORMA</h4>
                        <p class="leading-relaxed mb-2">A presente plataforma tem como objetivo exclusivo disponibilizar um ambiente digital para:</p>
                        <ul class="list-disc pl-5 space-y-1 mb-2">
                            <li>Cadastro de motoristas, transportadores e agenciadores;</li>
                            <li>Divulgação de fretes;</li>
                            <li>Intermediação de contato entre as partes.</li>
                        </ul>
                        <p class="leading-relaxed italic text-sm">
                            <span class="font-semibold">Importante:</span> A plataforma não realiza transporte, não intermedia pagamentos entre usuários e não participa da execução dos serviços de frete.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">3. RESPONSABILIDADES DA PLATAFORMA</h4>
                        <p class="leading-relaxed mb-2">A plataforma se limita a:</p>
                        <ul class="list-disc pl-5 space-y-1 mb-3">
                            <li>Disponibilizar espaço para cadastro e anúncios;</li>
                            <li>Manter o funcionamento do sistema;</li>
                            <li>Gerenciar planos, cobranças e acessos.</li>
                        </ul>
                        <p class="leading-relaxed mb-2">A plataforma não se responsabiliza por:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Qualidade, execução ou atraso nos serviços de frete;</li>
                            <li>Danos, perdas ou extravios de carga;</li>
                            <li>Conduta dos usuários cadastrados;</li>
                            <li>Negociações realizadas fora ou dentro da plataforma;</li>
                            <li>Informações fornecidas pelos usuários.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">4. RESPONSABILIDADE DOS USUÁRIOS</h4>
                        <p class="leading-relaxed mb-2">Os usuários (motoristas, transportadores e agenciadores) declaram que:</p>
                        <ul class="list-disc pl-5 space-y-1 mb-3">
                            <li>São responsáveis pelas informações fornecidas;</li>
                            <li>Possuem autorização legal para exercer suas atividades;</li>
                            <li>Respondem integralmente por serviços prestados;</li>
                            <li>São responsáveis por acordos, valores e condições negociadas.</li>
                        </ul>
                        <p class="leading-relaxed mb-2 font-semibold">É proibido:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Fornecer informações falsas;</li>
                            <li>Utilizar a plataforma para atividades ilegais;</li>
                            <li>Praticar golpes, fraudes ou condutas abusivas.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">5. PLANOS, VALORES E PAGAMENTOS</h4>
                        <p class="leading-relaxed mb-4">O uso da plataforma poderá ocorrer mediante planos pagos, conforme descrito abaixo:</p>

                        <h5 class="font-semibold text-white mb-1">5.1 Planos</h5>
                        <p class="leading-relaxed mb-2">A plataforma poderá oferecer:</p>
                        <ul class="list-disc pl-5 space-y-1 mb-3">
                            <li>Plano mensal</li>
                            <li>Plano trimestral</li>
                            <li>Plano anual</li>
                        </ul>
                        <p class="leading-relaxed mb-4">Os valores serão informados no momento da contratação e podem ser alterados a qualquer momento, mediante aviso prévio.</p>

                        <h5 class="font-semibold text-white mb-1">5.2 Formas de Pagamento</h5>
                        <p class="leading-relaxed mb-2">Os pagamentos poderão ser realizados por:</p>
                        <ul class="list-disc pl-5 space-y-1 mb-4">
                            <li>Cartão de crédito</li>
                            <li>Pix</li>
                            <li>Boleto bancário</li>
                        </ul>

                        <h5 class="font-semibold text-white mb-1">5.3 Política de Pagamento</h5>
                        <ul class="list-disc pl-5 space-y-1 mb-4">
                            <li>O acesso à plataforma será liberado após a confirmação do pagamento;</li>
                            <li>Em caso de inadimplência, o acesso poderá ser suspenso;</li>
                            <li>Não há garantia de fechamento de fretes ou retorno financeiro ao usuário.</li>
                        </ul>

                        <h5 class="font-semibold text-white mb-1">5.4 Reembolsos</h5>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Não haverá reembolso de valores já pagos, salvo em casos previstos em lei;</li>
                            <li>O cancelamento do plano não gera devolução proporcional de valores.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">6. CANCELAMENTO</h4>
                        <p class="leading-relaxed mb-2">O usuário poderá solicitar o cancelamento a qualquer momento, porém:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>O acesso permanecerá ativo até o final do período contratado;</li>
                            <li>Não haverá reembolso após a ativação do plano.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">7. PRIVACIDADE E DADOS</h4>
                        <p class="leading-relaxed mb-2">Ao se cadastrar, o usuário autoriza:</p>
                        <ul class="list-disc pl-5 space-y-1 mb-2">
                            <li>O uso de seus dados para funcionamento da plataforma;</li>
                            <li>O compartilhamento de informações com outros usuários para fins de contato profissional.</li>
                        </ul>
                        <p class="leading-relaxed">A plataforma se compromete a proteger os dados conforme a legislação vigente.</p>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">8. LIMITAÇÃO DE RESPONSABILIDADE</h4>
                        <p class="leading-relaxed mb-2">
                            A plataforma atua exclusivamente como classificados digitais, não sendo parte de qualquer negociação ou contrato firmado entre usuários.
                        </p>
                        <p class="leading-relaxed mb-2">Toda responsabilidade sobre:</p>
                        <ul class="list-disc pl-5 space-y-1 mb-2">
                            <li>Transporte</li>
                            <li>Pagamentos</li>
                            <li>Acordos comerciais</li>
                        </ul>
                        <p class="leading-relaxed">é exclusivamente dos usuários envolvidos.</p>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">9. PENALIDADES</h4>
                        <p class="leading-relaxed mb-2">A plataforma poderá, sem aviso prévio:</p>
                        <ul class="list-disc pl-5 space-y-1 mb-2">
                            <li>Suspender ou excluir contas;</li>
                            <li>Remover anúncios;</li>
                            <li>Bloquear usuários;</li>
                        </ul>
                        <p class="leading-relaxed mb-2 font-semibold">em caso de:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Violação destes termos;</li>
                            <li>Suspeita de fraude;</li>
                            <li>Conduta inadequada.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">10. ALTERAÇÕES DOS TERMOS</h4>
                        <p class="leading-relaxed">
                            A plataforma poderá alterar estes Termos de Uso a qualquer momento. O uso contínuo da plataforma após alterações implica na aceitação automática dos novos termos.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">11. DISPOSIÇÕES FINAIS</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Este termo rege a relação entre a plataforma e seus usuários;</li>
                            <li>Eventuais conflitos deverão ser resolvidos conforme a legislação brasileira;</li>
                            <li>Fica eleito o foro da comarca da sede da empresa para dirimir quaisquer dúvidas.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">12. CONTATO</h4>
                        <p class="leading-relaxed mb-4">
                            Para dúvidas, suporte ou solicitações, o usuário poderá entrar em contato através dos canais oficiais da plataforma.
                        </p>
                    </div>

                    <div class="mt-8 pt-4 border-t border-slate-800">
                        <p class="text-sm text-slate-500 text-center">
                            Última atualização: 30 de março de 2026.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
