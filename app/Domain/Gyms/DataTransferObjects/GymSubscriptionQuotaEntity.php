<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class GymSubscriptionQuotaEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?string $amount;
    public ?string $date;
    public ?string $state;
    public ?int $gym_subscription_id;

    public ?GymSubscriptionEntity $gymSubscription;
}
