<?php

declare(strict_types=1);

namespace App\Domains\Freight\Events;

use App\Domains\Freight\Models\Freight;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class FreightPublished implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public readonly Freight $freight)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('freights')];
    }

    public function broadcastAs(): string
    {
        return 'FreightPublished';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->freight->id,
            'origin_city' => $this->freight->origin_city,
            'destination_city' => $this->freight->destination_city,
            'price_cents' => $this->freight->price_cents,
        ];
    }
}

