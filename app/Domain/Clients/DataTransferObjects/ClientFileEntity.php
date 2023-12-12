<?php

namespace Domain\Clients\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class ClientFileEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public int $client_id;
    public string $file;
    public ?string $description;

    public ?ClientEntity $client;
}
