<?php

namespace Domain\TreatmentScheduleNotes\Providers;

use Domain\TreatmentScheduleNotes\Contracts\Repositories\TreatmentScheduleNotesRepository as TreatmentScheduleNotesRepositoryInterface;
use Domain\TreatmentScheduleNotes\Contracts\Services\TreatmentScheduleNotesService as TreatmentScheduleNotesServiceInterface;
use Domain\TreatmentScheduleNotes\Repositories\TreatmentScheduleNotesRepository;
use Domain\TreatmentScheduleNotes\Services\TreatmentScheduleNotesService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TreatmentScheduleNotesDomainServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            // Repositories
            TreatmentScheduleNotesRepositoryInterface::class,

            // Services
            TreatmentScheduleNotesServiceInterface::class,
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
        $this->app->bind(TreatmentScheduleNotesRepositoryInterface::class, TreatmentScheduleNotesRepository::class);

        // Services
        $this->app->bind(TreatmentScheduleNotesServiceInterface::class, TreatmentScheduleNotesService::class);
    }
}
