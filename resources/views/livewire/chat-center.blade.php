<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
    @if ($chatUnavailable ?? false)
        <section class="rounded-lg border border-amber-700 bg-amber-950/50 p-4 text-sm text-amber-200 lg:col-span-3">
            O modulo de chat ainda nao foi inicializado no banco de dados. Execute as migrations pendentes para ativar o chat.
        </section>
    @endif

    <section class="rounded-lg border border-slate-800 bg-slate-900 p-4 shadow-sm lg:col-span-1">
        <h3 class="text-sm font-semibold text-slate-200">Iniciar conversa</h3>
        <div class="mt-2 flex gap-2">
            <select wire:model="contactId" class="w-full rounded-md border-slate-700 bg-slate-950 text-sm text-slate-100">
                <option value="">Selecione um contato</option>
                @foreach ($contacts as $contact)
                    <option value="{{ $contact->id }}">{{ $contact->name }} ({{ $contact->profile_type->value ?? $contact->profile_type }})</option>
                @endforeach
            </select>
            <button wire:click="startThread" type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-500">Abrir</button>
        </div>

        <h3 class="mt-6 text-sm font-semibold text-slate-200">Conversas</h3>
        <div class="mt-2 space-y-2">
            @forelse ($threads as $thread)
                <button type="button" wire:click="$set('activeThreadId', '{{ $thread->id }}')" class="w-full rounded-md border px-3 py-2 text-left text-sm hover:bg-slate-800 {{ $activeThreadId === $thread->id ? 'border-cyan-500 bg-slate-800' : 'border-slate-700' }}">
                    <div class="font-medium text-slate-100">Conversa #{{ str($thread->id)->substr(0, 8) }}</div>
                    <div class="text-xs text-slate-400">{{ optional($thread->messages->first())->message ?? 'Sem mensagens' }}</div>
                </button>
            @empty
                <p class="text-sm text-slate-400">Nenhuma conversa iniciada.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-lg border border-slate-800 bg-slate-900 p-4 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-semibold text-slate-200">Mensagens</h3>

        <div class="mt-3 max-h-[380px] space-y-3 overflow-y-auto rounded-md border border-slate-800 bg-slate-950 p-3">
            @forelse ($messages as $message)
                <div class="rounded-md bg-slate-900 p-3 shadow-sm">
                    <div class="text-xs text-slate-400">{{ $message->sender->name ?? 'Usuario' }} · {{ optional($message->created_at)->format('d/m H:i') }}</div>
                    <p class="mt-1 text-sm text-slate-100">{{ $message->message }}</p>
                    @if (! empty($message->attachment_path))
                        <a href="{{ Storage::disk('public')->url($message->attachment_path) }}" target="_blank" class="mt-2 inline-flex text-xs text-cyan-300 hover:text-cyan-200">
                            Anexo: {{ $message->attachment_name ?? 'arquivo' }}
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-400">Selecione uma conversa para visualizar mensagens.</p>
            @endforelse
        </div>

        <div class="mt-3 flex gap-2">
            <input wire:model.defer="newMessage" type="text" placeholder="Digite uma mensagem..." class="w-full rounded-md border-slate-700 bg-slate-950 text-sm text-slate-100" />
            <input wire:model="attachment" type="file" class="w-56 rounded-md border-slate-700 bg-slate-950 text-xs text-slate-300" />
            <button wire:click="sendMessage" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-sm text-white hover:bg-indigo-500">Enviar</button>
        </div>
    </section>

    @once
        <script>
            document.addEventListener('livewire:init', () => {
                window.__chatRealtimeBound = window.__chatRealtimeBound || {};

                Livewire.on('refresh-thread-channel', (threadId) => {
                    if (!threadId || window.__chatRealtimeBound[threadId]) {
                        return;
                    }

                    window.__chatRealtimeBound[threadId] = true;
                    window.Echo.private(`chat.thread.${threadId}`).listen('.ChatMessageSent', () => {
                        window.Livewire?.dispatch('chat-message-sent');
                    });
                });
            });
        </script>
    @endonce
</div>




