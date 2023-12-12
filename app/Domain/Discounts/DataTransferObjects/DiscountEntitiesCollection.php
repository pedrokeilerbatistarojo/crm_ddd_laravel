<?php

namespace Domain\Discounts\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class DiscountEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return DiscountEntity::class;
    }
}
