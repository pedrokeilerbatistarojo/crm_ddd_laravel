<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class GymSubscriptionMemberAccessRightEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?int $member_id;
    public ?string $date_from;
    public ?string $date_to;
    public ?string $hour_from;
    public ?string $hour_to;
    public ?bool $monday_access;
    public ?bool $tuesday_access;
    public ?bool $wednesday_access;
    public ?bool $thursday_access;
    public ?bool $friday_access;
    public ?bool $saturday_access;
    public ?bool $sunday_access;
    public ?bool $unlimited_access;

    public ?GymSubscriptionMemberEntity $gymSubscriptionMember;

}
