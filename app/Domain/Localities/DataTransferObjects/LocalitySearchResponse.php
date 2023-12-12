<?php

namespace Domain\Localities\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class LocalitySearchResponse extends SearchResponse
{
    /**
     * @return LocalityEntitiesCollection
     */
    public function getData(): LocalityEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof LocalityEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . LocalityEntitiesCollection::class);
    }
}
