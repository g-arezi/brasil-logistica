<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domains\Chat\Models\ChatThread;
use App\Domains\User\Models\User;

final class ChatThreadPolicy
{
    public function view(User $user, ChatThread $thread): bool
    {
        return $thread->participants()->where('user_id', $user->id)->exists();
    }

    public function sendMessage(User $user, ChatThread $thread): bool
    {
        return $this->view($user, $thread) && $thread->status->value === 'open';
    }
}

