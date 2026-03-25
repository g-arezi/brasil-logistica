<?php

declare(strict_types=1);

namespace App\Domains\Chat\Actions;

use App\Domains\Chat\Events\ChatMessageSent;
use App\Domains\Chat\Models\ChatMessage;
use App\Domains\Chat\Models\ChatThread;
use App\Domains\User\Models\User;
use Illuminate\Validation\ValidationException;

final class SendChatMessageAction
{
    /**
     * @param array<string, mixed> $attachment
     */
    public function execute(ChatThread $thread, User $sender, string $messageText, array $attachment = []): ChatMessage
    {
        $messageText = trim($messageText);

        if ($messageText === '' && $attachment === []) {
            throw ValidationException::withMessages(['message' => ['Mensagem ou anexo obrigatorio.']]);
        }

        $isParticipant = $thread->participants()->whereKey($sender->id)->exists();

        if (! $isParticipant) {
            throw ValidationException::withMessages(['thread' => ['Usuario nao participa desta conversa.']]);
        }

        $message = $thread->messages()->create(array_filter([
            'sender_id' => $sender->id,
            'message' => $messageText,
            'attachment_path' => $attachment['path'] ?? null,
            'attachment_name' => $attachment['name'] ?? null,
            'attachment_mime' => $attachment['mime'] ?? null,
            'attachment_size' => $attachment['size'] ?? null,
        ], static fn ($value) => $value !== null));

        $thread->touch();

        ChatMessageSent::dispatch($message);

        return $message;
    }
}


