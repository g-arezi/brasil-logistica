<?php

declare(strict_types=1);

namespace App\Domains\Vehicle\Enums;

enum VehicleType: string
{
    case Truck = 'truck';
    case Bitrem = 'bitrem';
    case Sider = 'sider';
    case Van = 'van';
    case Carreta = 'carreta';
    case Outros = 'outros';
}
