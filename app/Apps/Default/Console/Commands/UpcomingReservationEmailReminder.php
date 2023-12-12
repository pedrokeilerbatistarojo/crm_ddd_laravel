<?php

namespace app\Apps\Default\Console\Commands;

use Domain\CircuitReservations\Contracts\Services\CircuitReservationsService;
use Domain\CircuitReservations\DataTransferObjects\CircuitReservationSearchRequest;
use Domain\TreatmentReservations\Contracts\Services\TreatmentReservationsService;
use Domain\TreatmentReservations\DataTransferObjects\TreatmentReservationSearchRequest;
use Illuminate\Console\Command;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class UpcomingReservationEmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upcoming-reservation-email-reminder {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to the client notifying the upcoming reservation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private readonly CircuitReservationsService $circuitReservationsService,
        private readonly TreatmentReservationsService $treatmentReservationsService,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws UnknownProperties
     */
    public function handle(): int
    {
        $date = $this->option('date') ?: date('Y-m-d', strtotime('+1 day'));

        $this->circuitReservationsService->search(
            new CircuitReservationSearchRequest([
                'filters' => ['date' => $date],
                'includes' => [],
                'paginateSize' => config('system.infinite_pagination')
            ])
        )
            ->getData()
            ->each(function ($record) {
                $this->circuitReservationsService->sendUpcomingReservationEmail($record->id);
            });

        $this->treatmentReservationsService->search(
            new TreatmentReservationSearchRequest([
                'filters' => ['date' => $date],
                'includes' => [],
                'paginateSize' => config('system.infinite_pagination')
            ])
        )
            ->getData()
            ->each(function ($record) {
                $this->treatmentReservationsService->sendUpcomingReservationEmail($record->id);
            });

        $this->info('Proceso finalizado.');

        return 0;
    }
}
