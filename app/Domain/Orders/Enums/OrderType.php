<?php

namespace Domain\Orders\Enums;

enum OrderType: string
{
    case CLIENT = 'Cliente';
    case TELEPHONE_SALE = 'Venta Telefónica';
    case COUNTER_SALE = 'Venta de Mostrador';
}
