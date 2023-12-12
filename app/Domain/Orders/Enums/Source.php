<?php

namespace Domain\Orders\Enums;

enum Source: string
{
    case WEB = 'Web';
    case CRM = 'CRM';
}
