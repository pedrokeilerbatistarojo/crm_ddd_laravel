<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class GymSubscriptionQuotaSearchResponse extends SearchResponse
{
    /**
     * @return GymSubscriptionQuotaEntitiesCollection
     */
    public function getData(): GymSubscriptionQuotaEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof GymSubscriptionQuotaEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . GymSubscriptionQuotaEntitiesCollection::class);
    }
}
