<?php

namespace Domain\Gyms\Providers;

use Domain\Gyms\Contracts\Repositories\GymFeeTypesRepository as GymFeeTypesRepositoryInterface;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionsRepository as GymSubscriptionsRepositoryInterface;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionMembersRepository as GymSubscriptionMembersRepositoryInterface;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionMemberAccessRepository as GymSubscriptionMemberAccessRepositoryInterface;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionMemberAccessRightsRepository as GymSubscriptionMemberAccessRightsRepositoryInterface;
use Domain\Gyms\Contracts\Repositories\GymSubscriptionQuotasRepository as GymSubscriptionQuotasRepositoryInterface;
use Domain\Gyms\Contracts\Services\GymsService as GymsServiceInterface;
use Domain\Gyms\Repositories\GymFeeTypesRepository;
use Domain\Gyms\Repositories\GymSubscriptionsRepository;
use Domain\Gyms\Repositories\GymSubscriptionMembersRepository;
use Domain\Gyms\Repositories\GymSubscriptionMemberAccessRepository;
use Domain\Gyms\Repositories\GymSubscriptionMemberAccessRightsRepository;
use Domain\Gyms\Repositories\GymSubscriptionQuotasRepository;
use Domain\Gyms\Services\GymsService;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class GymsDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            GymFeeTypesRepositoryInterface::class,
            GymSubscriptionsRepositoryInterface::class,
            GymSubscriptionMembersRepositoryInterface::class,
            GymSubscriptionMemberAccessRepositoryInterface::class,
            GymSubscriptionMemberAccessRightsRepositoryInterface::class,
            GymSubscriptionQuotasRepositoryInterface::class,

            // Services
            GymsServiceInterface::class,
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
        $this->app->bind(GymFeeTypesRepositoryInterface::class, GymFeeTypesRepository::class);
        $this->app->bind(GymSubscriptionsRepositoryInterface::class, GymSubscriptionsRepository::class);
        $this->app->bind(GymSubscriptionMembersRepositoryInterface::class, GymSubscriptionMembersRepository::class);
        $this->app->bind(GymSubscriptionMemberAccessRepositoryInterface::class, GymSubscriptionMemberAccessRepository::class);
        $this->app->bind(GymSubscriptionMemberAccessRightsRepositoryInterface::class, GymSubscriptionMemberAccessRightsRepository::class);
        $this->app->bind(GymSubscriptionQuotasRepositoryInterface::class, GymSubscriptionQuotasRepository::class);

        // Services
        $this->app->bind(GymsServiceInterface::class, GymsService::class);
    }
}
