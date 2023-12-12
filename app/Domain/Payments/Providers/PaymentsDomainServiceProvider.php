<?php

namespace Domain\Payments\Providers;

use Domain\Payments\Contracts\Repositories\PaymentsRepository as PaymentsRepositoryInterface;
use Domain\Payments\Contracts\Services\PaymentsService as PaymentsServiceInterface;
use Domain\Payments\Repositories\PaymentsRepository;
use Domain\Payments\Services\PaymentsService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PaymentsDomainServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            // Repositories
            PaymentsRepositoryInterface::class,

            // Services
            PaymentsServiceInterface::class,
        ];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(PaymentsRepositoryInterface::class, PaymentsRepository::class);

        // Services
        $this->app->bind(PaymentsServiceInterface::class, PaymentsService::class);
    }
}
