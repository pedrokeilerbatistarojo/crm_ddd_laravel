<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class EmployeeTimeOffEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return EmployeeTimeOffEntity::class;
    }
}
