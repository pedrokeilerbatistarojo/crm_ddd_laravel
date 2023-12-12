<?php

namespace Domain\Products\Enums;

enum PriceType: string
{
    case FIXED = 'Fijo';
    case CALCULATED = 'Calculado';
}
