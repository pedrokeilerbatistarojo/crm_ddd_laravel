<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class CategoryEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public string $name;
    public int $active;
}
