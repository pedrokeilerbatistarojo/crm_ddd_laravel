<?php

namespace Domain\Users\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class UserEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return UserEntity::class;
    }
}
