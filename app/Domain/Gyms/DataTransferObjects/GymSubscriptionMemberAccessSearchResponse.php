<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class GymSubscriptionMemberAccessSearchResponse extends SearchResponse
{
    /**
     * @return GymSubscriptionMemberAccessEntitiesCollection
     */
    public function getData(): GymSubscriptionMemberAccessEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof GymSubscriptionMemberAccessEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . GymSubscriptionMemberAccessEntitiesCollection::class);
    }
}
