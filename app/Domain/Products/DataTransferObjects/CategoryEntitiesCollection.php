<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class CategoryEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return CategoryEntity::class;
    }
}
