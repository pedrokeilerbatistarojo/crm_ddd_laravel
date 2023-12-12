<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class GymSubscriptionMemberAccessRightSearchResponse extends SearchResponse
{
    /**
     * @return GymSubscriptionMemberAccessRightEntitiesCollection
     */
    public function getData(): GymSubscriptionMemberAccessRightEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof GymSubscriptionMemberAccessRightEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . GymSubscriptionMemberAccessRightEntitiesCollection::class);
    }
}
