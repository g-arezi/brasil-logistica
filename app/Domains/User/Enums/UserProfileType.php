<?php

declare(strict_types=1);

namespace App\Domains\User\Enums;

enum UserProfileType: string
{
    case Driver = 'driver';
    case Transportadora = 'transportadora';
    case Agenciador = 'agenciador';
    case Admin = 'admin';
    case FreightistaLegacy = 'freightista';
    case Company = 'company';
}

