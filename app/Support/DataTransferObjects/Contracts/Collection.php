<?php

namespace Support\DataTransferObjects\Contracts;

use ArrayAccess;
use Illuminate\Contracts\Support\CanBeEscapedWhenCastToString;
use Illuminate\Support\Enumerable;

interface Collection extends ArrayAccess, CanBeEscapedWhenCastToString, Enumerable
{
    /**
     * @return string
     */
    public function getType(): string;
}
