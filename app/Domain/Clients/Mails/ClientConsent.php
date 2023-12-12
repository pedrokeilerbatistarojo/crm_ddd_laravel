<?php

namespace Domain\Clients\Mails;

use Barryvdh\DomPDF\Facade\Pdf;
use Domain\Clients\Contracts\Services\ClientsService;
use Dompdf\Adapter\CPDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientConsent extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $subject = "CONSENTIMIENTO EXPRESO CLIENTES THERMAS DE GRIÑÓN";

    /**
     * @param int $clientId
     * @param ClientsService $clientService
     */
    public function __construct(
        public readonly int $clientId,
        private readonly ClientsService $clientService
    ) {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $client = $this->clientService->find($this->clientId, []);

        return $this->view('emails.clients.consent', [
            'client' => $client
        ]);
    }
}
