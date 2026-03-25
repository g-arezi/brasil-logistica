<?php

declare(strict_types=1);

namespace App\Domains\Support\Events;

use App\Domains\Support\Models\SupportTicket;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class SupportTicketUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public readonly SupportTicket $ticket) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('support.ticket.'.$this->ticket->id)];
    }

    public function broadcastAs(): string
    {
        return 'SupportTicketUpdated';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->ticket->id,
            'status' => $this->ticket->status->value,
            'priority' => $this->ticket->priority->value,
            'updated_at' => optional($this->ticket->updated_at)?->toISOString(),
        ];
    }
}
