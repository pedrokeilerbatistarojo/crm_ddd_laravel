<?php

namespace Domain\Clients\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class ClientSearchResponse extends SearchResponse
{
    /**
     * @return ClientEntitiesCollection
     */
    public function getData(): ClientEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof ClientEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . ClientEntitiesCollection::class);
    }
}
