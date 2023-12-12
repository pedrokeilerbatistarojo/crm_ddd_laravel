<?php

namespace Domain\Orders\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class OrderApprovalEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public string $locator;
    public array $order_data;
    public bool $is_duplicated;
    public bool $is_reservation;
}
