<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class EmployeeOrderEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public string $date;
    public string $order;
}
