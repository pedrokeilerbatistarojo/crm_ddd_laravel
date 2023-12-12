<?php

namespace Domain\Users\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class UserSearchResponse extends SearchResponse
{
    /**
     * @return UserEntitiesCollection
     */
    public function getData(): UserEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof UserEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . UserEntitiesCollection::class);
    }
}
