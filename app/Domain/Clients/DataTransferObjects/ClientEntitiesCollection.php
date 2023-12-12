<?php

namespace Domain\Clients\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class ClientEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return ClientEntity::class;
    }
}
