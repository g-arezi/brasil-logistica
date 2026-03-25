<?php

declare(strict_types=1);

namespace App\Domains\Chat\Enums;

enum ChatThreadStatus: string
{
    case Open = 'open';
    case Closed = 'closed';
}
