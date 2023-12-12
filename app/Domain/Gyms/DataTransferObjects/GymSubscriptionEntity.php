<?php

namespace Domain\Gyms\DataTransferObjects;

use Domain\Clients\DataTransferObjects\ClientEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class GymSubscriptionEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $client_id;
    public ?int $gym_fee_type_id;
    public ?string $gym_fee_type_name;
    public ?string $price;
    public ?string $activation_date;
    public ?string $start_date;
    public ?string $end_date;
    public ?string $expiration_date;
    public ?int $payment_day;
    public ?int $biweekly_payment_day;
    public ?string $payment_type;

    public ?ClientEntity $client;
    public ?GymFeeTypeEntity $gymFeeType;

}
