<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class ProductEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $product_type_id;
    public ?string $image;
    public string $name;
    public ?string $short_description;
    public ?string $description;
    public float $price;
    public string $price_type;
    public int $circuit_sessions;
    public int $treatment_sessions;
    public bool $online_sale;
    public bool $editable;
    public bool $available;
    public int $priority;
    public int $active;
    public bool $all_reserves_on_same_day;
    public ?int $duration_treatment_schedule;
    public ?int $duration_circuit_schedule;
    public string $background_color;
    public string $text_color;

    public ?ProductTypeEntity $productType;
    public ?array $productDiscounts;
}
