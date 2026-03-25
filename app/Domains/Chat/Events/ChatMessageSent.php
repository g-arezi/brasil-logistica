<?php

declare(strict_types=1);

namespace App\Domains\Chat\Events;

use App\Domains\Chat\Models\ChatMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ChatMessageSent implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly ChatMessage $message) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.thread.'.$this->message->thread_id)];
    }

    public function broadcastAs(): string
    {
        return 'ChatMessageSent';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'thread_id' => $this->message->thread_id,
            'sender_id' => $this->message->sender_id,
            'message' => $this->message->message,
            'created_at' => optional($this->message->created_at)?->toISOString(),
        ];
    }
}
