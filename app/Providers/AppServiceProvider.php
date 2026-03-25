<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Chat\Models\ChatThread;
use App\Domains\Freight\Models\Freight;
use App\Domains\Freight\Observers\FreightObserver;
use App\Domains\Freight\Services\DistanceServiceInterface;
use App\Domains\Freight\Services\MapboxDistanceService;
use App\Domains\Support\Models\SupportTicket;
use App\Domains\User\Services\BrasilApiDocumentValidationService;
use App\Domains\User\Services\DocumentValidationServiceInterface;
use App\Policies\ChatThreadPolicy;
use App\Policies\SupportTicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DistanceServiceInterface::class, MapboxDistanceService::class);
        $this->app->bind(DocumentValidationServiceInterface::class, BrasilApiDocumentValidationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Freight::observe(FreightObserver::class);

        Gate::policy(ChatThread::class, ChatThreadPolicy::class);
        Gate::policy(SupportTicket::class, SupportTicketPolicy::class);
    }
}
