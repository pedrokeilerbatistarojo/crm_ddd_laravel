<?php

namespace Domain\Localities\DataTransferObjects;

use Support\DataTransferObjects\Entity;

class ProvinceEntity extends Entity
{
    public ?int $id;
    public string $name;
}
