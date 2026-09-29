<?php

namespace App\Actions\Reservation;

use App\Enums\CabinStatus;
use App\Models\Cabin;
use App\Models\Reservation;

final readonly class AvailabilityResult
{
    public function __construct(public bool $available, public ?string $reason = null) {}
}

class CheckCabinAvailability
{
    /**
     * Advisory pre-check with identical semantics to CreateReservationHold.
     *
     * NOT authoritative: the engine re-checks inside a DB transaction under
     * lockForUpdate. Always call the engine to create the hold; this result
     * may be stale under concurrency. Engine kept as-is on purpose so the
     * lock → re-check order stays intact.
     *
     * @return AvailabilityResult available + reason (null when available,
     *                            else invalid_dates | invalid_cabin | unavailable_status | over_capacity | date_conflict)
     */
    public static function run(int $cabinId, string $checkIn, string $checkOut, ?int $totalGuests = null): AvailabilityResult
    {
        if ($checkOut <= $checkIn) {
            return new AvailabilityResult(false, 'invalid_dates');
        }

        $cabin = Cabin::query()->whereKey($cabinId)->first();

        if ($cabin === null) {
            return new AvailabilityResult(false, 'invalid_cabin');
        }

        if ($cabin->status !== CabinStatus::Active) {
            return new AvailabilityResult(false, 'unavailable_status');
        }

        if ($totalGuests !== null && $totalGuests > $cabin->capacity) {
            return new AvailabilityResult(false, 'over_capacity');
        }

        $conflict = Reservation::query()->active()->overlapping($cabinId, $checkIn, $checkOut)->exists();

        if ($conflict) {
            return new AvailabilityResult(false, 'date_conflict');
        }

        return new AvailabilityResult(true);
    }
}
