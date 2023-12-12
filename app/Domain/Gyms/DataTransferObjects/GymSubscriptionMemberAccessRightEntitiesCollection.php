<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class GymSubscriptionMemberAccessRightEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return GymSubscriptionMemberAccessRightEntity::class;
    }
}
