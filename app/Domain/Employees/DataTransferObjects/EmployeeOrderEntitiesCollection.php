<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class EmployeeOrderEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return EmployeeOrderEntity::class;
    }
}
