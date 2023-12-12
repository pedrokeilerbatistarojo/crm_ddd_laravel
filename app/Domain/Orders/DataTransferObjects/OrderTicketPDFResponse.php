<?php

namespace Domain\Orders\DataTransferObjects;

use Support\DataTransferObjects\Response;

class OrderTicketPDFResponse extends Response
{
    /**
     * @return array
     */
    public function getData(): array
    {
        return parent::getData();
    }

}
