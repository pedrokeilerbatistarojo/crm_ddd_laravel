<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class EmployeeEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return EmployeeEntity::class;
    }
}
