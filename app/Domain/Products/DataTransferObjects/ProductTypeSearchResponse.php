<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class ProductTypeSearchResponse extends SearchResponse
{
    /**
     * @return ProductTypeEntitiesCollection
     */
    public function getData(): ProductTypeEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof ProductTypeEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . ProductTypeEntitiesCollection::class);
    }
}
