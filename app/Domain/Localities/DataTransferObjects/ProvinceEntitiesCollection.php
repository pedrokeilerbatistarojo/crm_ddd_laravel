<?php

namespace Domain\Localities\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class ProvinceEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return ProvinceEntity::class;
    }
}
