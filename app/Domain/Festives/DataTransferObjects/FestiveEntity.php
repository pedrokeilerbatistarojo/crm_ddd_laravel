<?php

namespace Domain\Festives\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class FestiveEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public string $date;
    public ?string $description;
    public string $type;
    public ?array $closed_hours;
}
