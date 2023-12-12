<?php

namespace Domain\TreatmentReservations\Mails;

use Domain\TreatmentReservations\Contracts\Services\TreatmentReservationsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpcomingReservation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param int $id
     * @param TreatmentReservationsService $service
     */
    public function __construct(
        public readonly int $id,
        private readonly TreatmentReservationsService $service
    ) {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $record = $this->service->find($this->id, ['orderDetails', 'client']);

//        return $this->to($record->client->email)
        return $this->to('info@thermasdegrinon.com')
            ->subject('Reserva Balneario Thermas de Griñon')
            ->markdown('emails.treatment_reservations.upcoming_reservation')
            ->with(['record' => $record]);
    }
}
