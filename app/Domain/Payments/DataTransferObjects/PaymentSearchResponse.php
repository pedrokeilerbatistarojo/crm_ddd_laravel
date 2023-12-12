<?php

namespace Domain\Payments\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class PaymentSearchResponse extends SearchResponse
{
    /**
     * @return PaymentEntitiesCollection
     */
    public function getData(): PaymentEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof PaymentEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . PaymentEntitiesCollection::class);
    }
}
