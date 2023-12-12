<?php

namespace Domain\Localities\DataTransferObjects;

use Support\Core\Enums\SQLSort;
use Support\DataTransferObjects\SearchRequest;

class ProvinceSearchRequest extends SearchRequest
{
    public string $sortField = 'name';
    public SQLSort $sortType = SQLSort::SORT_ASC;
}
