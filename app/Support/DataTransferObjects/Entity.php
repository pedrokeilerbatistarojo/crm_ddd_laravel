<?php

namespace Support\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class Entity extends DataTransferObject implements Contracts\Entity
{
    /**
     * @throws UnknownProperties
     */
    public function __construct(...$args)
    {
        if (count($args) === 1 && isset($args[0]['data'])) {
            $args = $args[0]['data'];
        }

        parent::__construct(...$args);
    }
}
