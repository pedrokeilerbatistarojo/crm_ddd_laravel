<?php

namespace Domain\SaleSessions\Enums;

enum SessionType: string
{
    case MORNING = 'Mañana';
    case AFTERNOON = 'Tarde';
}
