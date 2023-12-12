<?php

namespace Domain\Clients\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class ClientNoteSearchResponse extends SearchResponse
{
    /**
     * @return ClientNoteEntitiesCollection
     */
    public function getData(): ClientNoteEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof ClientNoteEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . ClientNoteEntitiesCollection::class);
    }
}
