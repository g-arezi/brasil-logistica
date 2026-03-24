<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

enum UserProfileType: string
{
    case Driver = 'driver';
    case Company = 'company';
}

