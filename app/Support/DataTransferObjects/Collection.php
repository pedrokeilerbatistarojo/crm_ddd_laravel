<?php

namespace Support\DataTransferObjects;

abstract class Collection extends \Illuminate\Support\Collection implements
    Contracts\Collection
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return static::class;
    }
}
