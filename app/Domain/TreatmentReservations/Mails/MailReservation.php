<?php

namespace Domain\TreatmentReservations\Mails;

use Domain\TreatmentReservations\Contracts\Services\TreatmentReservationsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailReservation extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @param array $data
     * @param TreatmentReservationsService $service
     */
    public function __construct(
        public readonly array $data,
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
        $record = $this->service->find($this->data['id'], ['client','orderDetails','orderDetails.order']);

        return $this->to($this->data['email'])
            ->subject('Reserva Balneario Thermas de Griñon')
            ->markdown('emails.treatment_reservations.mail_reservation')
            ->with(['record' => $record]);
    }
}
