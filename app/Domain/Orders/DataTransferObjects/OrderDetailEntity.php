<?php

namespace Domain\Orders\DataTransferObjects;

use Domain\Products\DataTransferObjects\ProductEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class OrderDetailEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public int $order_id;
    public ?int $product_id;
    public string $product_name;
    public float $price;
    public int $quantity;
    public ?int $circuit_sessions;
    public ?int $treatment_sessions;

    public ?OrderEntity $order;
    public ?ProductEntity $product;
    public ?array $circuitReservations;
    public ?array $treatmentReservations;
}
