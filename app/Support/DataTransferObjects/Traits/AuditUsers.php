<?php

namespace Support\DataTransferObjects\Traits;

use Domain\Users\DataTransferObjects\UserEntity;

trait AuditUsers
{
    public ?int $created_by;
    public ?int $last_modified_by;
    public ?UserEntity $createdByUser;
    public ?UserEntity $lastModifiedByUser;
}
