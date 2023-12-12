<?php

namespace Domain\Invoices\DataTransferObjects;

use Support\DataTransferObjects\EntityCollection;

class InvoiceEntitiesCollection extends EntityCollection
{
    public static function getEntityClass(): string
    {
        return InvoiceEntity::class;
    }
}
