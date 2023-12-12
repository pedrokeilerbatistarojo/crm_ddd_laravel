<?php

namespace Domain\Invoices\Enums;

enum InvoiceType: string
{
    case QUOTA = 'Cuota';
    case ORDER = 'Order';
    case CUSTOM = 'Custom';
}
