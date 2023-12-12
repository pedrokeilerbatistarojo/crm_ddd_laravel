<?php

namespace Domain\TreatmentScheduleNotes\DataTransferObjects;

use Support\Core\Enums\SQLSort;
use Support\DataTransferObjects\SearchRequest;

class TreatmentScheduleNoteSearchRequest extends SearchRequest
{
    public string $sortField = 'id';
    public SQLSort $sortType = SQLSort::SORT_DESC;
}
