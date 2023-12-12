<?php

namespace Domain\Employees\DataTransferObjects;

use Support\Core\Enums\SQLSort;
use Support\DataTransferObjects\SearchRequest;

class EmployeeTimeOffSearchRequest extends SearchRequest
{
    public string $sortField = 'employee_id';
    public SQLSort $sortType = SQLSort::SORT_ASC;
}
