<div class="space-y-5">
    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-6">
        <article class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">Usuarios</p>
            <p class="mt-2 text-2xl font-semibold text-slate-100">{{ number_format($totalUsers, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">Fretes</p>
            <p class="mt-2 text-2xl font-semibold text-slate-100">{{ number_format($totalFreights, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">Chats em aberto</p>
            <p class="mt-2 text-2xl font-semibold text-slate-100">{{ number_format($openThreads, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">Chamados abertos</p>
            <p class="mt-2 text-2xl font-semibold text-slate-100">{{ number_format($openTickets, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">SLA estourado</p>
            <p class="mt-2 text-2xl font-semibold text-rose-300">{{ number_format($overdueTickets, 0, ',', '.') }}</p>
        </article>
        <article class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-400">1a resposta media</p>
            <p class="mt-2 text-2xl font-semibold text-cyan-300">{{ number_format($avgFirstResponseMinutes, 0, ',', '.') }} min</p>
        </article>
    </section>

    <section class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-200">Usuarios por perfil</h3>
        <div class="mt-3 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-800 text-left text-slate-400">
                        <th class="px-3 py-2">Perfil</th>
                        <th class="px-3 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usersByProfile as $item)
                        <tr class="border-b border-slate-800/70 text-slate-200">
                            <td class="px-3 py-2">{{ strtoupper((string) $item->profile_type) }}</td>
                            <td class="px-3 py-2">{{ number_format((int) $item->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-xl border border-slate-800 bg-slate-900 p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-200">Fila de chamados (SLA)</h3>
        <div class="mt-3 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-800 text-left text-slate-400">
                        <th class="px-3 py-2">Assunto</th>
                        <th class="px-3 py-2">Prioridade</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Dono</th>
                        <th class="px-3 py-2">Atribuido</th>
                        <th class="px-3 py-2">Vencimento</th>
                        <th class="px-3 py-2">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ticketQueue as $ticket)
                        <tr class="border-b border-slate-800/70 text-slate-200">
                            <td class="px-3 py-2">{{ $ticket->subject }}</td>
                            <td class="px-3 py-2 uppercase">{{ $ticket->priority->value }}</td>
                            <td class="px-3 py-2 uppercase">{{ $ticket->status->value }}</td>
                            <td class="px-3 py-2">{{ $ticket->owner->name ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $ticket->assignee->name ?? '-' }}</td>
                            <td class="px-3 py-2">
                                @if ($ticket->due_at)
                                    <span class="{{ $ticket->due_at->isPast() ? 'text-rose-300' : 'text-slate-200' }}">{{ $ticket->due_at->format('d/m H:i') }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex flex-wrap gap-1">
                                    <button wire:click="assignTicket('{{ $ticket->id }}')" type="button" class="rounded bg-slate-700 px-2 py-1 text-xs hover:bg-slate-600">Assumir</button>
                                    <button wire:click="updateTicketStatus('{{ $ticket->id }}', 'in_progress')" type="button" class="rounded bg-indigo-700 px-2 py-1 text-xs hover:bg-indigo-600">Em andamento</button>
                                    <button wire:click="updateTicketStatus('{{ $ticket->id }}', 'resolved')" type="button" class="rounded bg-emerald-700 px-2 py-1 text-xs hover:bg-emerald-600">Resolver</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-6 text-center text-slate-400">Nenhum chamado pendente na fila.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>


