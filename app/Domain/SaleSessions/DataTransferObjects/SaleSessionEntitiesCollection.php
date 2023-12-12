<?php

namespace Domain\SaleSessions\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class SaleSessionEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return SaleSessionEntity::class;
    }
}
