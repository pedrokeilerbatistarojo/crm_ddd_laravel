<?php

namespace Domain\Invoices\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class InvoiceSearchResponse extends SearchResponse
{
    /**
     * @return InvoiceEntitiesCollection
     */
    public function getData(): InvoiceEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof InvoiceEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . InvoiceEntitiesCollection::class);
    }
}
