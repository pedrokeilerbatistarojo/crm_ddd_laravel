<?php

namespace Domain\Gyms\DataTransferObjects;

use Domain\Clients\DataTransferObjects\ClientEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class GymSubscriptionMemberEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $gym_subscription_id;
    public ?int $client_id;
    public ?string $date_from;
    public ?string $date_to;

    public ?ClientEntity $client;
    public ?GymSubscriptionEntity $gymSubscription;

}
