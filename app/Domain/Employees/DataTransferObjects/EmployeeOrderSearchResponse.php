<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class EmployeeOrderSearchResponse extends SearchResponse
{
    /**
     * @return EmployeeOrderEntitiesCollection
     */
    public function getData(): EmployeeOrderEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof EmployeeOrderEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . EmployeeOrderEntitiesCollection::class);
    }
}
