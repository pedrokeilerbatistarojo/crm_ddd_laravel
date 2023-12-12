<?php

namespace Domain\Orders\DataTransferObjects;

use Domain\Clients\DataTransferObjects\ClientEntity;
use Domain\Companies\DataTransferObjects\CompanyEntity;
use Domain\SaleSessions\DataTransferObjects\SaleSessionEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class OrderEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?string $locator;
    public ?int $sale_session_id;
    public ?int $company_id;
    public ?int $client_id;
    public string $source;
    public ?string $discount;
    public float $total_price;
    public ?string $ticket_number;
    public ?string $type;
    public ?int $telephone_sale_seq;
    public ?string $counter_sale_seq;
    public bool $used_purchase;
    public ?string $note;

    public ?SaleSessionEntity $saleSession;
    public ?CompanyEntity $company;
    public ?ClientEntity $client;
    public ?array $orderDetails;
    public ?array $payments;
}
