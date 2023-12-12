<?php

namespace Domain\Discounts\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class DiscountSearchResponse extends SearchResponse
{
    /**
     * @return DiscountEntitiesCollection
     */
    public function getData(): DiscountEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof DiscountEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . DiscountEntitiesCollection::class);
    }
}
