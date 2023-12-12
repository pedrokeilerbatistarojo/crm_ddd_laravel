<?php

namespace Domain\Festives\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class FestiveSearchResponse extends SearchResponse
{
    /**
     * @return FestiveEntitiesCollection
     */
    public function getData(): FestiveEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof FestiveEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . FestiveEntitiesCollection::class);
    }
}
