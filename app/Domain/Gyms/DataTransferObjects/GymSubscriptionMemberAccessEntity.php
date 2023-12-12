<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class GymSubscriptionMemberAccessEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $member_id;
    public ?string $date_from;
    public ?string $date_to;

    public ?GymSubscriptionMemberEntity $gymSubscriptionMember;
}
