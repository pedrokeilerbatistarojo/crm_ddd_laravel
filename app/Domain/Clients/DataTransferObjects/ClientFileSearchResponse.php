<?php

namespace Domain\Clients\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class ClientFileSearchResponse extends SearchResponse
{
    /**
     * @return ClientFileEntitiesCollection
     */
    public function getData(): ClientFileEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof ClientFileEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . ClientFileEntitiesCollection::class);
    }
}
