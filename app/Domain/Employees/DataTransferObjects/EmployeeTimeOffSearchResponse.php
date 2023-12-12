<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class EmployeeTimeOffSearchResponse extends SearchResponse
{
    /**
     * @return EmployeeTimeOffEntitiesCollection
     */
    public function getData(): EmployeeTimeOffEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof EmployeeTimeOffEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . EmployeeTimeOffEntitiesCollection::class);
    }
}
