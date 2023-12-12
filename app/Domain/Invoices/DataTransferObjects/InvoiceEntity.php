<?php

namespace Domain\Invoices\DataTransferObjects;

use Domain\Clients\DataTransferObjects\ClientEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class InvoiceEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $client_id;
    public ?string $number;
    public ?string $description;
    public ?string $invoice_type;
    public ?string $invoice_type_id;
    public ?string $invoice_date;
    public ?string $address;
    public ?string $zip_code;
    public ?string $locality;
    public ?string $province;
    public ?string $observations;

    public ?ClientEntity $client;
}
