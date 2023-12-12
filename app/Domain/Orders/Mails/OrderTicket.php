<?php

namespace Domain\Orders\Mails;

use Barryvdh\DomPDF\Facade\Pdf;
use Domain\Orders\Contracts\Services\OrdersService;
use Dompdf\Adapter\CPDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderTicket extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param int $orderId
     * @param OrdersService $ordersService
     */
    public function __construct(
        public readonly int $orderId,
        private readonly OrdersService $ordersService
    ) {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $order = $this->ordersService->find($this->orderId, ['orderDetails', 'payments', 'company', 'client']);

        $pdf = Pdf::setPaper(CPDF::$PAPER_SIZES['a6'])->loadView('pdf.ticket', [
            'data' => [
                'order' => $order
            ]
        ]);

        return $this->markdown('emails.orders.ticket')
            ->with(['order' => $order])
            ->subject('Ticket de Compra')
            ->attachData($pdf->output(), 'ticket.pdf', ['mime' => 'application/pdf']);
    }
}
