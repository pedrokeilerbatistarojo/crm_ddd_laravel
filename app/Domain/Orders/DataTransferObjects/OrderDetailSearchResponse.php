<?php

namespace Domain\Orders\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class OrderDetailSearchResponse extends SearchResponse
{
    /**
     * @return OrderDetailEntitiesCollection
     */
    public function getData(): OrderDetailEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof OrderDetailEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . OrderDetailEntitiesCollection::class);
    }
}
