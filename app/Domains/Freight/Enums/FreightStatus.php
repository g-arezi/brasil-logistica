<?php

declare(strict_types=1);

namespace App\Domains\Freight\Enums;

enum FreightStatus: string
{
    case Published = 'published';
    case Matched = 'matched';
    case Closed = 'closed';
}

