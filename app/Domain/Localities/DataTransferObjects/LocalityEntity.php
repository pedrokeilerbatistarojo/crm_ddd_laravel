<?php

namespace Domain\Localities\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\Timestamps;

class LocalityEntity extends Entity
{
    use Timestamps;

    public ?int $id;
    public string $zip_code;
    public string $municipio_id;
    public string $locality;
    public string $population_unit_code;
    public string $singular_entity_name;
    public string $population;
    public string $province_id;

    public ?ProvinceEntity $province;
}
