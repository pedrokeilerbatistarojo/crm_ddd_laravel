<?php

namespace Domain\Employees\DataTransferObjects;

use Support\Core\Enums\SQLSort;
use Support\DataTransferObjects\SearchRequest;

class EmployeeOrderSearchRequest extends SearchRequest
{
    public string $sortField = 'date';
    public SQLSort $sortType = SQLSort::SORT_ASC;
}
