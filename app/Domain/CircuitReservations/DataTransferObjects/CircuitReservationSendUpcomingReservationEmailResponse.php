<?php

namespace Domain\CircuitReservations\DataTransferObjects;

use Support\DataTransferObjects\Response;

class CircuitReservationSendUpcomingReservationEmailResponse extends Response
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return parent::getData();
    }

}
