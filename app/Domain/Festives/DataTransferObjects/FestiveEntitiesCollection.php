<?php

namespace Domain\Festives\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class FestiveEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return FestiveEntity::class;
    }
}
