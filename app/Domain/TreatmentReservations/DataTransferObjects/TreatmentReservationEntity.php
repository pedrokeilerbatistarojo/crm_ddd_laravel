<?php

namespace Domain\TreatmentReservations\DataTransferObjects;

use Domain\Clients\DataTransferObjects\ClientEntity;
use Domain\Employees\DataTransferObjects\EmployeeEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class TreatmentReservationEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $employee_id;
    public int $client_id;
    public ?string $date;
    public ?string $time;
    public ?int $duration;
    public bool $used;
    public ?string $notes;

    public ?ClientEntity $client;
    public ?EmployeeEntity $employee;
    public ?array $orderDetails;
}
