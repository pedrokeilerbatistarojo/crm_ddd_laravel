<?php

namespace Domain\CircuitReservations\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class CircuitReservationEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return CircuitReservationEntity::class;
    }
}
