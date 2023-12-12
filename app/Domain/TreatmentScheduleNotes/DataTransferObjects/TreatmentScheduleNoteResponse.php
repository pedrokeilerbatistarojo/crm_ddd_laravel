<?php

namespace Domain\TreatmentScheduleNotes\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class TreatmentScheduleNoteResponse extends SearchResponse
{
    /**
     * @return TreatmentScheduleNoteEntitiesCollection
     */
    public function getData(): TreatmentScheduleNoteEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof TreatmentScheduleNoteEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException('Passed data is not an instance of ' . TreatmentScheduleNoteEntitiesCollection::class);
    }
}
