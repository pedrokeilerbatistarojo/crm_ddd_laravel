<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class ProductSearchResponse extends SearchResponse
{
    /**
     * @return ProductEntitiesCollection
     */
    public function getData(): ProductEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof ProductEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . ProductEntitiesCollection::class);
    }
}
