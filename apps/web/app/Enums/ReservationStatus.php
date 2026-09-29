<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case PendingPayment = 'PENDING_PAYMENT';
    case Confirmed = 'CONFIRMED';
    case Expired = 'EXPIRED';
    case Cancelled = 'CANCELLED';
    case CheckedIn = 'CHECKED_IN';
    case Completed = 'COMPLETED';
}
