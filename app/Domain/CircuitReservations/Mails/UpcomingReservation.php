<?php

namespace Domain\CircuitReservations\Mails;

use Domain\CircuitReservations\Contracts\Services\CircuitReservationsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpcomingReservation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param int $id
     * @param CircuitReservationsService $service
     */
    public function __construct(
        public readonly int $id,
        private readonly CircuitReservationsService $service
    ) {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $record = $this->service->find($this->id, ['client']);

//        return $this->to($record->client->email)
        return $this->to('info@thermasdegrinon.com')
            ->subject('Reserva Circuito Agua - Balneario Thermas de Griñon')
            ->markdown('emails.circuit_reservations.upcoming_reservation')
            ->with(['record' => $record]);
    }
}
