<?php

namespace Domain\TreatmentScheduleNotes\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class TreatmentScheduleNoteEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return TreatmentScheduleNoteEntity::class;
    }
}
