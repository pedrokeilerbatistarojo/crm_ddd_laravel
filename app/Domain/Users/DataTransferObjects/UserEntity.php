<?php

namespace Domain\Users\DataTransferObjects;

use Domain\Companies\DataTransferObjects\CompanyEntity;
use Support\DataTransferObjects\Entity;
use Support\DataTransferObjects\Traits\AuditUsers;
use Support\DataTransferObjects\Traits\Timestamps;

class UserEntity extends Entity
{
    use AuditUsers;
    use Timestamps;

    public ?int $id;
    public string $username;
    public string $name;
    public string $email;
    public ?string $password;
    public ?string $email_verified_at;
    public ?string $remember_token;
    public ?int $default_company_id;
    public ?bool $active;

    public ?CompanyEntity $company;
}
