<?php

namespace Domain\TreatmentReservations\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class TreatmentReservationEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return TreatmentReservationEntity::class;
    }
}
