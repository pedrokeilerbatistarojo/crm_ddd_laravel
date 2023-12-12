<?php

namespace Domain\TreatmentReservations\DataTransferObjects;

use Support\Core\Enums\SQLSort;
use Support\DataTransferObjects\SearchRequest;

class TreatmentReservationSearchRequest extends SearchRequest
{
    public string $sortField = 'id';
    public SQLSort $sortType = SQLSort::SORT_DESC;
}
