<?php

namespace Domain\Localities\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class ProvinceSearchResponse extends SearchResponse
{
    /**
     * @return ProvinceEntitiesCollection
     */
    public function getData(): ProvinceEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof ProvinceEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . ProvinceEntitiesCollection::class);
    }
}
