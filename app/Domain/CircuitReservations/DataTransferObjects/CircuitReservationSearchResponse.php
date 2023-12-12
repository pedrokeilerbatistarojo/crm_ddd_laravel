<?php

namespace Domain\CircuitReservations\DataTransferObjects;

use Support\DataTransferObjects\SearchResponse;
use Support\Exceptions\InvalidDataTypeException;

class CircuitReservationSearchResponse extends SearchResponse
{
    /**
     * @return CircuitReservationEntitiesCollection
     */
    public function getData(): CircuitReservationEntitiesCollection
    {
        return parent::getData();
    }

    /**
     * @throws InvalidDataTypeException
     */
    public function setData(mixed $data): static
    {
        if ($data === null || $data instanceof CircuitReservationEntitiesCollection) {
            return parent::setData($data);
        }

        throw new InvalidDataTypeException(
            'Passed data is not an instance of ' . CircuitReservationEntitiesCollection::class
        );
    }
}
