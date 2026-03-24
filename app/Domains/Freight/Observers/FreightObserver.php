<?php

declare(strict_types=1);

namespace App\Domains\Freight\Observers;

use App\Domains\Freight\Jobs\SendFreightWebhookJob;
use App\Domains\Freight\Models\Freight;

final class FreightObserver
{
    public function created(Freight $freight): void
    {
        SendFreightWebhookJob::dispatch($freight->id);
    }
}

