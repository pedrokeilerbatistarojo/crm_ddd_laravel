<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class ProductEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return ProductEntity::class;
    }
}
