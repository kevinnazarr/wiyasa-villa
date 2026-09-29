<?php

namespace App\Enums;

enum CabinStatus: string
{
    case Active = 'ACTIVE';
    case Inactive = 'INACTIVE';
    case Maintenance = 'MAINTENANCE';
}
