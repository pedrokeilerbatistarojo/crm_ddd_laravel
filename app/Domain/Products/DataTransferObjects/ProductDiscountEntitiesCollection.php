<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class ProductDiscountEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return ProductDiscountEntity::class;
    }
}
