<?php

namespace Domain\CircuitReservations\Mails;

use Domain\CircuitReservations\Contracts\Services\CircuitReservationsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailReservation extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @param array $data
     * @param CircuitReservationsService $service
     */
    public function __construct(
        public readonly array $data,
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
        $record = $this->service->find($this->data['id'], ['client','orderDetails','orderDetails.order','orderDetails.product']);

        return $this->to($this->data['email'])
            ->subject('Reserva Circuito Agua - Balneario Thermas de Griñon')
            ->markdown('emails.circuit_reservations.mail_reservation')
            ->with(['record' => $record]);
    }
}
