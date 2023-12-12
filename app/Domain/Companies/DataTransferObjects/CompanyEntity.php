<?php

namespace Domain\Companies\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\Timestamps;

class CompanyEntity extends Entity
{
    use Timestamps;

    public ?int $id;
    public string $name;
    public ?string $cif;
    public ?string $email;
    public ?string $phone;
    public ?string $address;
    public ?string $zip_code;
    public ?string $locality;
    public ?string $province;
}
