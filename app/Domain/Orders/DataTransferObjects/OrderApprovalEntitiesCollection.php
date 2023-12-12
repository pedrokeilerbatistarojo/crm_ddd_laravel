<?php

namespace Domain\Orders\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class OrderApprovalEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return OrderApprovalEntity::class;
    }
}
