<?php

namespace Domain\Orders\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class OrderApprovalSearchResponse extends SearchResponse
{
    /**
     * @return OrderApprovalEntitiesCollection
     */
    public function getData(): OrderApprovalEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof OrderApprovalEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . OrderApprovalEntitiesCollection::class);
    }
}
