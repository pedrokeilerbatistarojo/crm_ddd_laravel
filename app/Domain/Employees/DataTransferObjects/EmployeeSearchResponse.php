<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class EmployeeSearchResponse extends SearchResponse
{
    /**
     * @return EmployeeEntitiesCollection
     */
    public function getData(): EmployeeEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof EmployeeEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . EmployeeEntitiesCollection::class);
    }
}
