<?php

namespace Domain\Clients\DataTransferObjects;

use Domain\Localities\DataTransferObjects\LocalityEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class ClientEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?string $email;
    public ?string $document;
    public string $name;
    public ?string $phone;
    public ?string $birthdate;
    public ?string $address;
    public ?string $postcode;
    public ?string $locality_id;
    public bool $opt_in;
    public ?bool $lopd_agree;

    public ?LocalityEntity $locality;
    public ?array $clientNotes;
    public ?array $clientFiles;
}
