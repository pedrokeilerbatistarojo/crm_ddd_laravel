<?php

namespace Domain\SaleSessions\DataTransferObjects;

use Domain\Employees\DataTransferObjects\EmployeeEntity;
use Domain\Users\DataTransferObjects\UserEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class SaleSessionEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $closed_by;
    public int $employee_id;
    public string $session_status;
    public string $session_type;
    public string $start_date;
    public ?string $end_date;
    public float $start_amount;
    public ?float $end_amount;

    public ?UserEntity $closedByUser;
    public ?EmployeeEntity $employee;
    public ?array $orders;
}
