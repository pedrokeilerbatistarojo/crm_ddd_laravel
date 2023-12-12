<?php

namespace Domain\Products\DataTransferObjects;

use Domain\Discounts\DataTransferObjects\DiscountEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class ProductDiscountEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public int $product_id;
    public int $discount_id;
    public float $price;

    public ?DiscountEntity $discount;
}
