<?php

declare(strict_types=1);

namespace App\Domains\Support\Enums;

enum SupportTicketPriority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Critical = 'critical';
}

