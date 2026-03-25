<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domains\Support\Models\SupportTicket;
use App\Domains\User\Models\User;

final class SupportTicketPolicy
{
    public function view(User $user, SupportTicket $ticket): bool
    {
        return $user->profile_type->value === 'admin' || $ticket->owner_id === $user->id || $ticket->assigned_to === $user->id;
    }

    public function update(User $user, SupportTicket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    public function resolve(User $user, SupportTicket $ticket): bool
    {
        return $user->profile_type->value === 'admin' || $ticket->owner_id === $user->id;
    }

    public function assign(User $user, SupportTicket $ticket): bool
    {
        return $user->profile_type->value === 'admin';
    }
}

