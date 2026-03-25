<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\User\Enums\UserStatus;
use App\Domains\User\Enums\UserProfileType;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
            $targetUser->status = $enumStatus;

            if ($enumStatus === UserStatus::Approved) {
                // We can set document_verified_at dynamically
                if ($targetUser->document_verified_at === null) {
                    $targetUser->document_verified_at = now();
                }
            } elseif ($enumStatus === UserStatus::Rejected) {
                $targetUser->document_verified_at = null;
            }

            $targetUser->save();
        }
    }

    public function render(): View
    {
        $query = User::query()
            ->where('id', '!=', auth()->id())
            ->latest();

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                  ->orWhere('email', 'ilike', '%' . $this->search . '%')
                  ->orWhere('document_number', 'ilike', '%' . $this->search . '%');
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
