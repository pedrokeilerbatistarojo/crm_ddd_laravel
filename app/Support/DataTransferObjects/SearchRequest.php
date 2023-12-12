<?php

namespace Support\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;
use Support\Core\Enums\SQLSort;

class SearchRequest extends DataTransferObject
{
    public array $filters;
    public array $includes = [];
    public int $paginateSize = 10;
    public string $sortField;
    public SQLSort $sortType;
}
