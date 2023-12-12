<?php

namespace Domain\CircuitReservations\Providers;

use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationOrderDetailsRepository as CircuitReservationOrderDetailsRepositoryInterface;
use Domain\CircuitReservations\Contracts\Repositories\CircuitReservationsRepository as CircuitReservationsRepositoryInterface;
use Domain\CircuitReservations\Contracts\Services\CircuitReservationsService as CircuitReservationsServiceInterface;
use Domain\CircuitReservations\Repositories\CircuitReservationsRepository;
use Domain\CircuitReservations\Repositories\CircuitReservationOrderDetailsRepository;
use Domain\CircuitReservations\Services\CircuitReservationsService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CircuitReservationsDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            CircuitReservationsRepositoryInterface::class,
            CircuitReservationOrderDetailsRepositoryInterface::class,

            // Services
            CircuitReservationsServiceInterface::class,
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
        $this->app->bind(CircuitReservationsRepositoryInterface::class, CircuitReservationsRepository::class);
        $this->app->bind(CircuitReservationOrderDetailsRepositoryInterface::class, CircuitReservationOrderDetailsRepository::class);

        // Services
        $this->app->bind(CircuitReservationsServiceInterface::class, CircuitReservationsService::class);
    }
}
