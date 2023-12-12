<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class ProductTypeEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return ProductTypeEntity::class;
    }
}
