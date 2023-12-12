<?php

namespace Support\DataTransferObjects\Contracts;

interface EntityCollection extends Collection
{
    /**
     * @param array $data
     * @return static
     */
    public static function createFromArray(array $data): static;

    /**
     * @return string
     */
    public static function getEntityClass(): string;
}
