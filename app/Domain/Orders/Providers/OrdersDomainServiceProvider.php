<?php

namespace Domain\Orders\Providers;

use Domain\Orders\Contracts\Repositories\OrdersApprovalRepository as OrdersApprovalRepositoryInterface;
use Domain\Orders\Contracts\Repositories\OrderDetailsRepository as OrderDetailsRepositoryInterface;
use Domain\Orders\Contracts\Repositories\OrdersRepository as OrdersRepositoryInterface;
use Domain\Orders\Contracts\Services\OrdersService as OrdersServiceInterface;
use Domain\Orders\Models\OrderDetail;
use Domain\Orders\Observers\OrderDetailObserver;
use Domain\Orders\Repositories\OrdersApprovalRepository;
use Domain\Orders\Repositories\OrderDetailsRepository;
use Domain\Orders\Repositories\OrdersRepository;
use Domain\Orders\Services\OrdersService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class OrdersDomainServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        OrderDetail::observe(OrderDetailObserver::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            // Repositories
            OrdersRepositoryInterface::class,

            // Services
            OrdersServiceInterface::class,
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
        $this->app->bind(OrdersRepositoryInterface::class, OrdersRepository::class);
        $this->app->bind(OrdersApprovalRepositoryInterface::class, OrdersApprovalRepository::class);
        $this->app->bind(OrderDetailsRepositoryInterface::class, OrderDetailsRepository::class);

        // Services
        $this->app->bind(OrdersServiceInterface::class, OrdersService::class);
    }
}
