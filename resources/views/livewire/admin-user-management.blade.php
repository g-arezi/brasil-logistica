<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between gap-4">
        <h3 class="text-lg font-semibold text-slate-200">Gerenciamento de Usuarios</h3>

        <div class="flex gap-2">
            <button wire:click="$set('showCreateModal', true)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                Novo Usuario
            </button>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nome, email, documento..." class="rounded-md border-slate-700 bg-slate-900 text-sm text-slate-100 shadow-sm w-64" />

            <select wire:model.live="statusFilter" class="rounded-md border-slate-700 bg-slate-900 text-sm text-slate-100 shadow-sm">
                <option value="">Todos os Status</option>
                <option value="pending">Pendentes</option>
                <option value="approved">Aprovados</option>
                <option value="rejected">Reprovados</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-slate-800 bg-slate-900 shadow-sm">
        <table class="min-w-full text-left text-sm text-slate-200">
            <thead class="border-b border-slate-800 bg-slate-800/50 text-slate-300">
                <tr>
                    <th class="px-4 py-3 font-semibold">Usuario</th>
                    <th class="px-4 py-3 font-semibold">Perfil</th>
                    <th class="px-4 py-3 font-semibold">Documento</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Plano Expira Em</th>
                    <th class="px-4 py-3 font-semibold">Cadastrado em</th>
                    <th class="px-4 py-3 font-semibold">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
                @forelse ($usersList as $userItem)
                    <tr class="hover:bg-slate-800/30">
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-100">{{ $userItem->name }}</div>
                            <div class="text-xs text-slate-400">{{ $userItem->email }}</div>
                        </td>
                        <td class="px-4 py-3 uppercase text-xs tracking-wider">{{ $userItem->profile_type?->value ?? 'N/A' }}</td>
                        <td class="px-4 py-3">{{ $userItem->document_number ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($userItem->status?->value === 'pending' || $userItem->status === 'pending')
                                <span class="inline-flex rounded-full bg-amber-500/10 px-2 py-1 text-xs font-semibold text-amber-400 border border-amber-500/20">
                                    Pendente
                                </span>
                            @elseif ($userItem->status?->value === 'approved' || $userItem->status === 'approved')
                                <span class="inline-flex rounded-full bg-emerald-500/10 px-2 py-1 text-xs font-semibold text-emerald-400 border border-emerald-500/20">
                                    Aprovado
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-rose-500/10 px-2 py-1 text-xs font-semibold text-rose-400 border border-rose-500/20">
                                    Reprovado
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-300">
                            @if ($userItem->profile_type?->value === 'admin')
                                Vitalicio
                            @else
                                {{ $userItem->subscription_expires_at?->format('d/m/Y') ?? 'Expirado' }}
                                <div class="mt-1 flex gap-1">
                                    <button wire:click="addSubscriptionDays({{ $userItem->id }}, 15)" class="text-[10px] bg-indigo-600/80 hover:bg-indigo-500 text-white px-1.5 py-0.5 rounded">+15d</button>
                                    <button wire:click="addSubscriptionDays({{ $userItem->id }}, 30)" class="text-[10px] bg-indigo-600/80 hover:bg-indigo-500 text-white px-1.5 py-0.5 rounded">+30d</button>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-400">{{ $userItem->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <button wire:click="viewUser({{ $userItem->id }})" type="button" class="rounded bg-indigo-600/90 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500 transition-colors">Ver</button>

                                @if (($userItem->status?->value ?? $userItem->status) !== 'approved')
                                    <button wire:click="updateStatus({{ $userItem->id }}, 'approved')" type="button" class="rounded bg-emerald-600/90 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-500 transition-colors">Aprovar</button>
                                @endif

                                @if (($userItem->status?->value ?? $userItem->status) !== 'rejected')
                                    <button wire:click="updateStatus({{ $userItem->id }}, 'rejected')" type="button" class="rounded bg-rose-600/90 px-3 py-1.5 text-xs font-medium text-white hover:bg-rose-500 transition-colors">Reprovar</button>
                                @endif

                                @if (($userItem->status?->value ?? $userItem->status) !== 'pending')
                                    <button wire:click="updateStatus({{ $userItem->id }}, 'pending')" type="button" class="rounded bg-slate-700 px-3 py-1.5 text-xs font-medium text-slate-200 hover:bg-slate-600 transition-colors">Pendente</button>
                                @endif

                                <button wire:click="deleteUser({{ $userItem->id }})" wire:confirm="Tem certeza que deseja excluir esta conta permanentemente?" type="button" class="rounded border border-red-500/50 bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-500 hover:bg-red-500/20 transition-colors ml-2">Excluir</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                            Nenhum usuario encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $usersList->links(data: ['scrollTo' => false]) }}
    </div>

    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-md rounded-lg bg-slate-800 p-6 shadow-xl">
                <h4 class="text-lg font-semibold text-slate-200 mb-4">Criar Novo Usuario</h4>

                <form wire:submit.prevent="createUser" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300">Nome</label>
                        <input type="text" wire:model="newName" class="mt-1 block w-full rounded-md border-slate-700 bg-slate-900 text-sm text-slate-100 shadow-sm" required />
                        @error('newName') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">E-mail</label>
                        <input type="email" wire:model="newEmail" class="mt-1 block w-full rounded-md border-slate-700 bg-slate-900 text-sm text-slate-100 shadow-sm" required />
                        @error('newEmail') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">Senha</label>
                        <input type="password" wire:model="newPassword" class="mt-1 block w-full rounded-md border-slate-700 bg-slate-900 text-sm text-slate-100 shadow-sm" required />
                        @error('newPassword') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">Documento (CPF/CNPJ)</label>
                        <input type="text" wire:model="newDocumentNumber" class="mt-1 block w-full rounded-md border-slate-700 bg-slate-900 text-sm text-slate-100 shadow-sm" required />
                        @error('newDocumentNumber') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">Perfil</label>
                        <select wire:model="newProfileType" class="mt-1 block w-full rounded-md border-slate-700 bg-slate-900 text-sm text-slate-100 shadow-sm" required>
                            <option value="driver">Motorista</option>
                            <option value="transportadora">Transportadora</option>
                            <option value="agenciador">Agenciador</option>
                            <option value="company">Empresa</option>
                            <option value="admin">Administrador</option>
                        </select>
                        @error('newProfileType') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-600">Cancelar</button>
                        <button type="submit" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">Salvar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showViewModal && $selectedUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-lg rounded-lg bg-slate-800 p-6 shadow-xl border border-slate-700">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="text-xl font-bold text-slate-100">Detalhes do Usuario</h4>
                    <button wire:click="closeViewModal" class="text-slate-400 hover:text-white transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-900 border border-slate-700 p-3 rounded">
                            <span class="block text-xs font-medium text-slate-400 mb-1">Nome Completo</span>
                            <span class="text-white">{{ $selectedUser->name }}</span>
                        </div>
                        <div class="bg-slate-900 border border-slate-700 p-3 rounded">
                            <span class="block text-xs font-medium text-slate-400 mb-1">E-mail</span>
                            <span class="text-white truncate">{{ $selectedUser->email }}</span>
                        </div>
                        <div class="bg-slate-900 border border-slate-700 p-3 rounded">
                            <span class="block text-xs font-medium text-slate-400 mb-1">CPF / CNPJ</span>
                            <span class="text-white">{{ $selectedUser->document_number ?? 'Não informado' }}</span>
                        </div>
                        <div class="bg-slate-900 border border-slate-700 p-3 rounded">
                            <span class="block text-xs font-medium text-slate-400 mb-1">Tipo de Perfil</span>
                            <span class="text-white uppercase">{{ $selectedUser->profile_type?->value ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-900 border border-slate-700 p-3 rounded">
                            <span class="block text-xs font-medium text-slate-400 mb-1">Status da Conta</span>
                            <span class="text-white uppercase">{{ $selectedUser->status?->value ?? 'N/A' }}</span>
                        </div>
                        <div class="bg-slate-900 border border-slate-700 p-3 rounded">
                            <span class="block text-xs font-medium text-slate-400 mb-1">Data de Cadastro</span>
                            <span class="text-white">{{ $selectedUser->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                        </div>
                        <div class="col-span-2 bg-slate-900 border border-slate-700 p-3 rounded">
                            <span class="block text-xs font-medium text-slate-400 mb-1">Validade do Plano</span>
                            <span class="text-white font-medium">
                                @if($selectedUser->profile_type?->value === 'admin')
                                    Conta Vitalícia (Administrador)
                                @else
                                    {{ $selectedUser->subscription_expires_at ? $selectedUser->subscription_expires_at->format('d/m/Y \à\s H:i') : 'Não definido/Expirado' }}
                                    @if($selectedUser->subscription_expires_at && $selectedUser->subscription_expires_at->isFuture())
                                        <span class="text-emerald-400 ml-2">({{ max(0, (int) ceil(now()->floatDiffInDays($selectedUser->subscription_expires_at, false))) }} dias restantes)</span>
                                    @else
                                        <span class="text-rose-400 ml-2">(Expirado)</span>
                                    @endif
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-700">
                    <button wire:click="deleteUser({{ $selectedUser->id }})" wire:confirm="Isso apagará essa conta de forma permanente. Tem certeza?" type="button" class="rounded-md bg-red-600/90 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500">
                        Excluir Conta
                    </button>
                    <button type="button" wire:click="closeViewModal" class="rounded-md bg-slate-700 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-slate-600">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

