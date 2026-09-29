<?php

namespace App\Enums;

enum ReservationSource: string
{
    case DirectWebsite = 'DIRECT_WEBSITE';
    case AdminManual = 'ADMIN_MANUAL';
    case Other = 'OTHER';
}
