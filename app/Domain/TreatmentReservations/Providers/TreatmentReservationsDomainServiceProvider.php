<?php

namespace Domain\TreatmentReservations\Providers;

use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationOrderDetailsRepository as TreatmentReservationOrderDetailsRepositoryInterface;
use Domain\TreatmentReservations\Contracts\Repositories\TreatmentReservationsRepository as TreatmentReservationsRepositoryInterface;
use Domain\TreatmentReservations\Contracts\Services\TreatmentReservationsService as TreatmentReservationsServiceInterface;
use Domain\TreatmentReservations\Repositories\TreatmentReservationOrderDetailsRepository;
use Domain\TreatmentReservations\Repositories\TreatmentReservationsRepository;
use Domain\TreatmentReservations\Services\TreatmentReservationsService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TreatmentReservationsDomainServiceProvider extends ServiceProvider implements DeferrableProvider
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
            TreatmentReservationsRepositoryInterface::class,
            TreatmentReservationOrderDetailsRepositoryInterface::class,

            // Services
            TreatmentReservationsServiceInterface::class,
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
        $this->app->bind(TreatmentReservationsRepositoryInterface::class, TreatmentReservationsRepository::class);
        $this->app->bind(
            TreatmentReservationOrderDetailsRepositoryInterface::class,
            TreatmentReservationOrderDetailsRepository::class
        );

        // Services
        $this->app->bind(TreatmentReservationsServiceInterface::class, TreatmentReservationsService::class);
    }
}
