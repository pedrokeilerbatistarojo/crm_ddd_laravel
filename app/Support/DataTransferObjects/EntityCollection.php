<?php

namespace Support\DataTransferObjects;

abstract class EntityCollection extends Collection implements
    Contracts\EntityCollection
{
    public static function createFromArray(array $data): static
    {
        $self = new static();

        foreach ($data as $entityData) {
            $self->push(app($self::getEntityClass(), ['args' => $entityData]));
        }

        return $self;
    }

    abstract public static function getEntityClass(): string;
}
