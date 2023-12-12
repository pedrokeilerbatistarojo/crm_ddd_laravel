<?php

namespace Domain\Clients\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class ClientFileEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return ClientFileEntity::class;
    }
}
