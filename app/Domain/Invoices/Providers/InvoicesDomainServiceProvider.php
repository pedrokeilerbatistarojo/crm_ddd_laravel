<?php

namespace Domain\Invoices\Providers;

use Domain\Invoices\Contracts\Repositories\InvoicesRepository as InvoicesRepositoryInterface;
use Domain\Invoices\Contracts\Services\InvoicesService as InvoicesServiceInterface;
use Domain\Invoices\Repositories\InvoicesRepository;
use Domain\Invoices\Services\InvoicesService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class InvoicesDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            InvoicesRepositoryInterface::class,

            // Services
            InvoicesServiceInterface::class,
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
        $this->app->bind(InvoicesRepositoryInterface::class, InvoicesRepository::class);

        // Services
        $this->app->bind(InvoicesServiceInterface::class, InvoicesService::class);
    }
}
