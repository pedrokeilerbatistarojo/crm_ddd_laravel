<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class GymFeeTypeEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return GymFeeTypeEntity::class;
    }
}
