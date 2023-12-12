<?php

namespace Domain\Users\Providers;

use Domain\Users\Contracts\Repositories\UsersRepository as UsersRepositoryInterface;
use Domain\Users\Contracts\Services\UsersService as UsersServiceInterface;
use Domain\Users\Repositories\UsersRepository;
use Domain\Users\Services\UsersService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UsersDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            UsersRepositoryInterface::class,

            // Services
            UsersServiceInterface::class,
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
        $this->app->bind(UsersRepositoryInterface::class, UsersRepository::class);

        // Services
        $this->app->bind(UsersServiceInterface::class, UsersService::class);
    }
}
