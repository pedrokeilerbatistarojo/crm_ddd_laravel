<?php

namespace Domain\Gyms\Enums;

enum GymFeeTypePeriodType: string
{
    case BIWEEKLY = 'quincenal';
    case MONTHLY = 'mensual';
    case QUARTERLY = 'trimestral';
    case BIANNUAL = 'semestral';
    case ANNUAL = 'anual';
}
