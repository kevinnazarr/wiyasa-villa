<?php

use App\Actions\Reservation\CheckCabinAvailability;
use App\Actions\Reservation\CreateReservationHold;
use App\Models\Cabin;
use App\Models\User;

function availabilityInput(User $user, Cabin $cabin, array $overrides = []): array
{
    return array_merge([
        'user_id' => $user->id,
        'cabin_id' => $cabin->id,
        'check_in' => '2026-10-10',
        'check_out' => '2026-10-12',
        'adults' => 2,
        'children' => 0,
        'infants' => 0,
        'total_guests' => 2,
    ], $overrides);
}

test('available cabin returns available', function () {
    $cabin = Cabin::factory()->create();

    $result = CheckCabinAvailability::run($cabin->id, '2026-10-10', '2026-10-12', 2);

    expect($result->available)->toBeTrue()
        ->and($result->reason)->toBeNull();
});

test('inactive cabin rejects availability', function () {
    $cabin = Cabin::factory()->inactive()->create();

    $result = CheckCabinAvailability::run($cabin->id, '2026-10-10', '2026-10-12', 2);

    expect($result->available)->toBeFalse()
        ->and($result->reason)->toBe('unavailable_status');
});

test('maintenance cabin rejects availability', function () {
    $cabin = Cabin::factory()->maintenance()->create();

    $result = CheckCabinAvailability::run($cabin->id, '2026-10-10', '2026-10-12', 2);

    expect($result->available)->toBeFalse()
        ->and($result->reason)->toBe('unavailable_status');
});

test('over-capacity rejects availability', function () {
    $cabin = Cabin::factory()->create(['capacity' => 4]);

    $result = CheckCabinAvailability::run($cabin->id, '2026-10-10', '2026-10-12', 5);

    expect($result->available)->toBeFalse()
        ->and($result->reason)->toBe('over_capacity');
});

test('overlapping active hold blocks availability', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    CreateReservationHold::run(availabilityInput($user, $cabin));

    $result = CheckCabinAvailability::run($cabin->id, '2026-10-11', '2026-10-13', 2);

    expect($result->available)->toBeFalse()
        ->and($result->reason)->toBe('date_conflict');
});

test('adjacent stays are available', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    CreateReservationHold::run(availabilityInput($user, $cabin));

    $result = CheckCabinAvailability::run($cabin->id, '2026-10-12', '2026-10-14', 2);

    expect($result->available)->toBeTrue()
        ->and($result->reason)->toBeNull();
});

test('expired hold is available', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    $old = CreateReservationHold::run(availabilityInput($user, $cabin));
    $old->update(['hold_expires_at' => now()->subHour()]);

    $result = CheckCabinAvailability::run($cabin->id, '2026-10-10', '2026-10-12', 2);

    expect($result->available)->toBeTrue()
        ->and($result->reason)->toBeNull();
});

test('invalid cabin is not available', function () {
    $result = CheckCabinAvailability::run(999999, '2026-10-10', '2026-10-12', 2);

    expect($result->available)->toBeFalse()
        ->and($result->reason)->toBe('invalid_cabin');
});

test('invalid dates are not available', function () {
    $cabin = Cabin::factory()->create();

    $sameDay = CheckCabinAvailability::run($cabin->id, '2026-10-12', '2026-10-12', 2);
    $reversed = CheckCabinAvailability::run($cabin->id, '2026-10-14', '2026-10-12', 2);

    expect($sameDay->available)->toBeFalse()
        ->and($sameDay->reason)->toBe('invalid_dates')
        ->and($reversed->available)->toBeFalse()
        ->and($reversed->reason)->toBe('invalid_dates');
});
