<?php

namespace Domain\Clients\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class ClientNoteEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public int $client_id;
    public string $note;

    public ?ClientEntity $client;
}
