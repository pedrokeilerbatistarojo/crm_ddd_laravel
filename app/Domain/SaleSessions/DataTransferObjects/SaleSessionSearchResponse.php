<?php

namespace Domain\SaleSessions\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class SaleSessionSearchResponse extends SearchResponse
{
    /**
     * @return SaleSessionEntitiesCollection
     */
    public function getData(): SaleSessionEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof SaleSessionEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . SaleSessionEntitiesCollection::class);
    }
}
