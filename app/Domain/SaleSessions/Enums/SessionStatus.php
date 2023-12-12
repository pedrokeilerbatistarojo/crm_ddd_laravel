<?php

namespace Domain\SaleSessions\Enums;

enum SessionStatus: string
{
    case OPEN = 'Abierta';
    case CLOSED = 'Cerrada';
    case REOPENED = 'Reabierta';
}
