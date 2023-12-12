<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class CategorySearchResponse extends SearchResponse
{
    /**
     * @return CategoryEntitiesCollection
     */
    public function getData(): CategoryEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof CategoryEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . CategoryEntitiesCollection::class);
    }
}
