<?php

declare(strict_types=1);

namespace App\Domains\Support\Enums;

enum SupportTicketStatus: string
{
    case Open = 'open';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
}
