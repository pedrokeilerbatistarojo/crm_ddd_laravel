<?php

namespace Domain\Discounts\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class DiscountEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public string $name;
}
