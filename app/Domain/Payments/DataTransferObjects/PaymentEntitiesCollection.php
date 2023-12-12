<?php

namespace Domain\Payments\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class PaymentEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return PaymentEntity::class;
    }
}
