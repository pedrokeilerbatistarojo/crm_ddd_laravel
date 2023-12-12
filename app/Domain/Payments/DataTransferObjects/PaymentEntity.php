<?php

namespace Domain\Payments\DataTransferObjects;

use Domain\Orders\DataTransferObjects\OrderEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class PaymentEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public int $order_id;
    public string $due_date;
    public string $paid_date;
    public string $type;
    public float $amount;
    public float $paid_amount;
    public float $returned_amount;

    public ?OrderEntity $order;
}
