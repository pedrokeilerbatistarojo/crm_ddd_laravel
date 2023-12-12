<?php

namespace Domain\Gyms\Enums;

enum GymSubscriptionPaymentType: string
{
    case CASH = 'efectivo';
    case CARD = 'tarjeta';
    case TRANSFER = 'transferencia';
}
