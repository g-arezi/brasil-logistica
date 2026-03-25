<?php

use App\Domains\Chat\Models\ChatThread;
use App\Domains\Support\Models\SupportTicket;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.thread.{threadId}', function ($user, string $threadId): bool {
    return ChatThread::query()
        ->whereKey($threadId)
        ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
        ->exists();
});

Broadcast::channel('support.ticket.{ticketId}', function ($user, string $ticketId): bool {
    $ticket = SupportTicket::query()->find($ticketId);

    if ($ticket === null) {
        return false;
    }

    return $ticket->owner_id === $user->id || $user->profile_type?->value === 'admin';
});
