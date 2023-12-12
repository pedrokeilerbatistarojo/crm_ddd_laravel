<?php

namespace Domain\Employees\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class EmployeeEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public string $first_name;
    public ?string $last_name;
    public ?string $second_last_name;
    public ?string $email;
    public ?string $phone;
    public bool $active;
    public bool $is_specialist;
}
