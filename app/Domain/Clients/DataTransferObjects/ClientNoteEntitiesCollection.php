<?php

namespace Domain\Clients\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class ClientNoteEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return ClientNoteEntity::class;
    }
}
