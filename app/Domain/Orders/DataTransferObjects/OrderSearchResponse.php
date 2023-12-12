<?php

namespace Domain\Orders\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class OrderSearchResponse extends SearchResponse
{
    /**
     * @return OrderEntitiesCollection
     */
    public function getData(): OrderEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof OrderEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . OrderEntitiesCollection::class);
    }
}
