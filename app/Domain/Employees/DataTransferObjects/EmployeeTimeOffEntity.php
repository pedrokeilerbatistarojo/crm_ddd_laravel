<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class EmployeeTimeOffEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $employee_id;
    public string $type;
    public string $from_date;
    public string $to_date;

    public ?EmployeeEntity $employee;
}
