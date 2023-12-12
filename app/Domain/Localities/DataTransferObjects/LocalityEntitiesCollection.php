<?php

namespace Domain\Localities\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class LocalityEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return LocalityEntity::class;
    }
}
