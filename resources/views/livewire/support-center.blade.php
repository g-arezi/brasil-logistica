<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
    @if ($supportUnavailable ?? false)
        <section class="rounded-lg border border-amber-700 bg-amber-950/50 p-4 text-sm text-amber-200 lg:col-span-3">
            O modulo de suporte ainda nao foi inicializado no banco de dados. Execute as migrations pendentes para ativar o suporte.
        </section>
    @endif

    <section class="rounded-lg border border-slate-800 bg-slate-900 p-4 shadow-sm lg:col-span-1">
        <h3 class="text-sm font-semibold text-slate-200">Abrir chamado</h3>
        <div class="mt-3 space-y-2">
            <input wire:model.defer="subject" type="text" placeholder="Assunto" class="w-full rounded-md border-slate-700 bg-slate-950 text-sm text-slate-100" />
            <select wire:model.defer="category" class="w-full rounded-md border-slate-700 bg-slate-950 text-sm text-slate-100">
                @foreach ($categories as $category)
                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                @endforeach
            </select>
            <select wire:model.defer="priority" class="w-full rounded-md border-slate-700 bg-slate-950 text-sm text-slate-100">
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->value }}">{{ ucfirst($priority->value) }}</option>
                @endforeach
            </select>
            <textarea wire:model.defer="description" rows="4" placeholder="Descreva o problema" class="w-full rounded-md border-slate-700 bg-slate-950 text-sm text-slate-100"></textarea>
            <input wire:model="ticketAttachment" type="file" class="w-full rounded-md border-slate-700 bg-slate-950 text-xs text-slate-300" />
            <button wire:click="createTicket" type="button" class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-500">Criar chamado</button>
        </div>

        <h3 class="mt-6 text-sm font-semibold text-slate-200">Meus chamados</h3>
        <div class="mt-2 space-y-2">
            @forelse ($tickets as $ticket)
                <button type="button" wire:click="$set('activeTicketId', '{{ $ticket->id }}')" class="w-full rounded-md border px-3 py-2 text-left text-sm hover:bg-slate-800 {{ $activeTicketId === $ticket->id ? 'border-cyan-500 bg-slate-800' : 'border-slate-700' }}">
                    <div class="font-medium text-slate-100">{{ $ticket->subject }}</div>
                    <div class="text-xs text-slate-400">{{ strtoupper($ticket->status->value) }} · {{ strtoupper($ticket->priority->value) }}</div>
                </button>
            @empty
                <p class="text-sm text-slate-400">Nenhum chamado registrado.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-lg border border-slate-800 bg-slate-900 p-4 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-semibold text-slate-200">Detalhes do chamado</h3>

        @if ($activeTicket)
            <div class="mt-3 rounded-md border border-slate-800 bg-slate-950 p-3 text-sm text-slate-200">
                <p><span class="font-medium">Assunto:</span> {{ $activeTicket->subject }}</p>
                <p><span class="font-medium">Status:</span> {{ strtoupper($activeTicket->status->value) }}</p>
                <p><span class="font-medium">Categoria:</span> {{ ucfirst($activeTicket->category) }}</p>
            </div>

            <div class="mt-3 max-h-[300px] space-y-3 overflow-y-auto rounded-md border border-slate-800 bg-slate-950 p-3">
                @foreach ($messages as $message)
                    <div class="rounded-md bg-slate-900 p-3 shadow-sm">
                        <div class="text-xs text-slate-400">{{ $message->sender->name ?? 'Usuario' }} · {{ optional($message->created_at)->format('d/m H:i') }}</div>
                        <p class="mt-1 text-sm text-slate-100">{{ $message->message }}</p>
                        @if (! empty($message->attachment_path))
                            <a href="{{ Storage::disk('public')->url($message->attachment_path) }}" target="_blank" class="mt-2 inline-flex text-xs text-cyan-300 hover:text-cyan-200">
                                Anexo: {{ $message->attachment_name ?? 'arquivo' }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-3 flex gap-2">
                <input wire:model.defer="newMessage" type="text" placeholder="Responder chamado..." class="w-full rounded-md border-slate-700 bg-slate-950 text-sm text-slate-100" />
                <input wire:model="messageAttachment" type="file" class="w-56 rounded-md border-slate-700 bg-slate-950 text-xs text-slate-300" />
                <button wire:click="addMessage" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-500">Enviar</button>
                <button wire:click="resolveTicket" type="button" class="rounded-md bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-500">Resolver</button>
            </div>
        @else
            <p class="mt-3 text-sm text-slate-400">Selecione um chamado para visualizar o histórico.</p>
        @endif
    </section>
</div>




