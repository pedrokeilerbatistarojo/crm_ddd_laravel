<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class ProductDiscountSearchResponse extends SearchResponse
{
    /**
     * @return ProductDiscountEntitiesCollection
     */
    public function getData(): ProductDiscountEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof ProductDiscountEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . ProductDiscountEntitiesCollection::class);
    }
}
