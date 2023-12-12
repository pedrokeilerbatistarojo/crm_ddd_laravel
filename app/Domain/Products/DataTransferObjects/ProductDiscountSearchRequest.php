<?php

namespace Domain\Products\DataTransferObjects;

use Support\Core\Enums\SQLSort;
use Support\DataTransferObjects\SearchRequest;

class ProductDiscountSearchRequest extends SearchRequest
{
    public string $sortField = 'id';
    public SQLSort $sortType = SQLSort::SORT_ASC;
}
