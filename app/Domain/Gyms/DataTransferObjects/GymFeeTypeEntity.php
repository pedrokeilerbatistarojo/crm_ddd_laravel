<?php

namespace Domain\Gyms\DataTransferObjects;

use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class GymFeeTypeEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public ?string $name;
    public ?string $price;
    public ?string $period_type;
    public ?int $payment_day;
    public int $duration_number_of_days;
    public ?int $biweekly_payment_day;
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

}
