<?php

namespace Domain\Products\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class ProductTypeEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $category_id;
    public string $name;
    public string $background_color;
    public string $text_color;
    public int $priority;
    public int $active;

    public ?CategoryEntity $category;
}
