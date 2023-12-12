<?php

namespace Domain\Products\DataTransferObjects;

use Support\Core\Enums\SQLSort;
use Support\DataTransferObjects\SearchRequest;

class CategorySearchRequest extends SearchRequest
{
    public string $sortField = 'name';
    public SQLSort $sortType = SQLSort::SORT_ASC;
}
