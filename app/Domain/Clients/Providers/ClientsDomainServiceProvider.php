<?php

namespace Domain\Clients\Providers;

use Domain\Clients\Contracts\Repositories\ClientsRepository as ClientsRepositoryInterface;
use Domain\Clients\Contracts\Repositories\ClientNotesRepository as ClientNotesRepositoryInterface;
use Domain\Clients\Contracts\Repositories\ClientFilesRepository as ClientFilesRepositoryInterface;
use Domain\Clients\Contracts\Services\ClientsService as ClientsServiceInterface;
use Domain\Clients\Repositories\ClientsRepository;
use Domain\Clients\Repositories\ClientNotesRepository;
use Domain\Clients\Repositories\ClientFilesRepository;
use Domain\Clients\Services\ClientsService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ClientsDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            ClientsRepositoryInterface::class,

            // Services
            ClientsServiceInterface::class,
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
        $this->app->bind(ClientsRepositoryInterface::class, ClientsRepository::class);
        $this->app->bind(ClientNotesRepositoryInterface::class, ClientNotesRepository::class);
        $this->app->bind(ClientFilesRepositoryInterface::class, ClientFilesRepository::class);

        // Services
        $this->app->bind(ClientsServiceInterface::class, ClientsService::class);
    }
}
