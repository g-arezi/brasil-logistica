<?php

declare(strict_types=1);

namespace App\Domains\Freight\Jobs;

use App\Domains\Freight\Models\Freight;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

final class SendFreightWebhookJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly string $freightId)
    {
    }

    public function handle(): void
    {
        /** @var Freight|null $freight */
        $freight = Freight::query()->find($this->freightId);

        if ($freight === null) {
            return;
        }

        $url = (string) config('services.n8n.freight_webhook_url');

        if ($url === '') {
            return;
        }

        Http::asJson()->post($url, [
            'freight_id' => $freight->id,
            'origin_city' => $freight->origin_city,
            'destination_city' => $freight->destination_city,
            'price_cents' => $freight->price_cents,
            'required_vehicle_type' => $freight->required_vehicle_type->value,
            'status' => $freight->status->value,
        ]);
    }
}

