<?php

namespace Domain\Orders\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class OrderEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return OrderEntity::class;
    }
}
