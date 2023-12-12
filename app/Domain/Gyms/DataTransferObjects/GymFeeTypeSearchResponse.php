<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class GymFeeTypeSearchResponse extends SearchResponse
{
    /**
     * @return GymFeeTypeEntitiesCollection
     */
    public function getData(): GymFeeTypeEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof GymFeeTypeEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . GymFeeTypeEntitiesCollection::class);
    }
}
