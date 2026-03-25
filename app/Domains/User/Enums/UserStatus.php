<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

enum UserStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}

