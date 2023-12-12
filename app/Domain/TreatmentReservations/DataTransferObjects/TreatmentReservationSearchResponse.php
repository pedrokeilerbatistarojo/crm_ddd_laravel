<?php

namespace Domain\TreatmentReservations\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class TreatmentReservationSearchResponse extends SearchResponse
{
    /**
     * @return TreatmentReservationEntitiesCollection
     */
    public function getData(): TreatmentReservationEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof TreatmentReservationEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException(
            'Passed data is not an instance of ' . TreatmentReservationEntitiesCollection::class
        );
    }
}
