<?php

namespace Domain\Payments\Enums;

enum PaymentType: string
{
    case CASH = 'Efectivo';
    case CC = 'Tarjeta de Crédito';
    case WIRE_TRANSFER = 'Transferencia';
}
