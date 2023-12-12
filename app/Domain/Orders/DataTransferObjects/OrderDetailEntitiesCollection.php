<?php

namespace Domain\Orders\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class OrderDetailEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return OrderDetailEntity::class;
    }
}
