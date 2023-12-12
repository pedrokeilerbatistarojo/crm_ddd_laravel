<?php

namespace Domain\Employees\DataTransferObjects;

use Support\Core\Enums\SQLSort;
use Support\DataTransferObjects\SearchRequest;

class EmployeeSearchRequest extends SearchRequest
{
    public string $sortField = 'first_name';
    public SQLSort $sortType = SQLSort::SORT_ASC;
}
