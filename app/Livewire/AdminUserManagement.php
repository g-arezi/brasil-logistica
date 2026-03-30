<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\User\Enums\UserProfileType;
use App\Domains\User\Enums\UserStatus;
use App\Mail\AccountStatusNotification;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class AdminUserManagement extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public bool $showCreateModal = false;

    public string $newName = '';

    public string $newEmail = '';

    public string $newPassword = '';

    public string $newProfileType = 'driver';

    public string $newDocumentNumber = '';

    public bool $showViewModal = false;

    public ?User $selectedUser = null;

    public function rules()
    {
        return [
            'newName' => 'required|string|max:255',
            'newEmail' => 'required|email|unique:users,email',
            'newPassword' => 'required|string|min:8',
            'newProfileType' => 'required|in:admin,driver,transportadora,agenciador,company',
            'newDocumentNumber' => 'required|string|unique:users,document_number',
        ];
    }

    public function createUser(): void
    {
        $user = auth()->user();

        if ($user === null || $user->profile_type->value !== 'admin') {
            abort(403, 'Apenas administradores podem criar usuarios.');
        }

        $this->validate();

        $profileType = UserProfileType::from($this->newProfileType);

        User::create([
            'name' => $this->newName,
            'email' => $this->newEmail,
            'password' => Hash::make($this->newPassword),
            'profile_type' => $profileType,
            'document_number' => preg_replace('/\D+/', '', $this->newDocumentNumber),
            'status' => UserStatus::Approved,
            'document_verified_at' => now(),
            'email_verified_at' => now(),
        ]);

        $this->reset(['newName', 'newEmail', 'newPassword', 'newProfileType', 'newDocumentNumber', 'showCreateModal']);
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updateStatus(int $userId, string $status): void
    {
        $user = auth()->user();

        if ($user === null || $user->profile_type->value !== 'admin') {
            abort(403, 'Apenas administradores podem alterar o status de usuarios.');
        }

        $targetUser = User::query()->find($userId);

        if ($targetUser === null) {
            return;
        }

        if ($targetUser->id === $user->id) {
            return; // Cannot change own status
        }

        $enumStatus = UserStatus::tryFrom($status);
        if ($enumStatus !== null) {
            $oldStatus = $targetUser->status;
            $targetUser->status = $enumStatus;

            if ($enumStatus === UserStatus::Approved) {
                // We can set document_verified_at dynamically
                if ($targetUser->document_verified_at === null) {
                    $targetUser->document_verified_at = now();
                }
                if ($oldStatus !== UserStatus::Approved) {
                    try {
                        Mail::to($targetUser->email)->send(new AccountStatusNotification(
                            $targetUser,
                            'approved',
                            'Sua conta foi aprovada com sucesso! Agora você já pode acessar a plataforma.'
                        ));
                    } catch (\Exception $e) {
                        Log::error('Erro ao enviar email de aprovação: '.$e->getMessage());
                    }
                }
            } elseif ($enumStatus === UserStatus::Rejected) {
                $targetUser->document_verified_at = null;
                if ($oldStatus !== UserStatus::Rejected) {
                    try {
                        Mail::to($targetUser->email)->send(new AccountStatusNotification(
                            $targetUser,
                            'rejected',
                            'Sua conta foi reprovada em nossa avaliação. Entre em contato para mais detalhes.'
                        ));
                    } catch (\Exception $e) {
                        Log::error('Erro ao enviar email de reprovação: '.$e->getMessage());
                    }
                }
            }

            $targetUser->save();
        }
    }

    public function addSubscriptionDays(int $userId, int $days): void
    {
        $user = User::findOrFail($userId);

        $currentExpiry = $user->subscription_expires_at && $user->subscription_expires_at->isFuture()
            ? $user->subscription_expires_at
            : now();

        $user->update([
            'subscription_expires_at' => $currentExpiry->addDays($days),
        ]);

        try {
            Mail::to($user->email)->send(new AccountStatusNotification(
                $user,
                'days_added',
                "Foram adicionados {$days} dias à sua assinatura. Seu plano agora é válido até ".$user->subscription_expires_at->format('d/m/Y').'.'
            ));
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de adição de dias: '.$e->getMessage());
        }

        $this->dispatch('notify', "Foram adicionados {$days} dias ao plano do usuário.");
    }

    public function expireSubscription(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update([
            'subscription_expires_at' => now()->subDays(1),
            'is_exempt_from_subscription' => false,
        ]);

        try {
            Mail::to($user->email)->send(new AccountStatusNotification(
                $user,
                'expired',
                'Sua assinatura expirou. Por favor, regularize seu pagamento para continuar publicando fretes.'
            ));
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de expiração: '.$e->getMessage());
        }

        $this->dispatch('notify', 'O plano do usuário foi expirado com sucesso.');
    }

    public function toggleSubscriptionExemption(int $userId): void
    {
        $user = auth()->user();

        if ($user === null || $user->profile_type->value !== 'admin') {
            abort(403, 'Apenas administradores podem isentar usuarios do pagamento.');
        }

        $targetUser = User::query()->find($userId);

        if ($targetUser !== null) {
            $targetUser->is_exempt_from_subscription = ! $targetUser->is_exempt_from_subscription;
            $targetUser->save();

            $status = $targetUser->is_exempt_from_subscription ? 'isento' : 'não isento';

            try {
                $msg = $targetUser->is_exempt_from_subscription
                    ? 'Você foi ISENTO do pagamento da assinatura. Agora você pode utilizar a plataforma gratuitamente.'
                    : 'Sua isenção de assinatura foi revogada. O pagamento regular será necessário para realizar certas ações.';

                Mail::to($targetUser->email)->send(new AccountStatusNotification($targetUser, 'exemption', $msg));
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de isenção: '.$e->getMessage());
            }

            if ($this->selectedUser && $this->selectedUser->id === $userId) {
                $this->selectedUser = $targetUser; // Update view
            }
        }
    }

    public function viewUser(int $userId): void
    {
        $user = auth()->user();

        if ($user === null || $user->profile_type->value !== 'admin') {
            abort(403, 'Acesso negado.');
        }

        $this->selectedUser = User::find($userId);
        if ($this->selectedUser) {
            $this->showViewModal = true;
        }
    }

    public function closeViewModal(): void
    {
        $this->showViewModal = false;
        $this->selectedUser = null;
    }

    public function deleteUser(int $userId): void
    {
        $user = auth()->user();

        if ($user === null || $user->profile_type->value !== 'admin') {
            abort(403, 'Apenas administradores podem excluir usuarios.');
        }

        $targetUser = User::find($userId);

        if ($targetUser === null) {
            return;
        }

        if ($targetUser->id === $user->id || $targetUser->email === 'admin@demo.com') {
            // Prevenir excluir o próprio usuário ou o superadmin principal
            return;
        }

        // Tentar excluir dependências ou deixar cascade DB hand-lo (Dependendo de foreign keys)
        // Aqui apenas deletamos o usuário, ou podemos inativá-lo
        $targetUser->delete();

        if ($this->selectedUser && $this->selectedUser->id === $userId) {
            $this->closeViewModal();
        }
    }

    public function render(): View
    {
        $query = User::query()
            ->where('id', '!=', auth()->id())
            ->latest();

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('email', 'ilike', '%'.$this->search.'%')
                    ->orWhere('document_number', 'ilike', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.admin-user-management', [
            'usersList' => $query->paginate(15),
        ]);
    }
}
