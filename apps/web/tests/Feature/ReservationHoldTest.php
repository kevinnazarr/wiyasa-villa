<?php

use App\Actions\Reservation\CreateReservationHold;
use App\Models\Cabin;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Validation\ValidationException;

function holdInput(User $user, Cabin $cabin, array $overrides = []): array
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
        'subtotal_amount' => 1800000,
        'extra_guest_amount' => 0,
        'discount_amount' => 0,
        'total_amount' => 1800000,
    ], $overrides);
}

test('hold ok creates pending reservation with hold expiry', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();

    $reservation = CreateReservationHold::run(holdInput($user, $cabin));

    expect($reservation->status->value)->toBe('PENDING_PAYMENT')
        ->and($reservation->booking_code)->toStartWith('WVS-')
        ->and($reservation->hold_expires_at)->not->toBeNull();
});

test('overlapping reservation is rejected', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    CreateReservationHold::run(holdInput($user, $cabin));

    expect(fn () => CreateReservationHold::run(holdInput($user, $cabin, [
        'check_in' => '2026-10-11',
        'check_out' => '2026-10-13',
    ])))->toThrow(ValidationException::class);
});

test('adjacent stays are allowed', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    CreateReservationHold::run(holdInput($user, $cabin));

    $next = CreateReservationHold::run(holdInput($user, $cabin, [
        'check_in' => '2026-10-12',
        'check_out' => '2026-10-14',
    ]));

    expect($next->id)->toBeGreaterThan(0);
});

test('expired hold does not block new hold', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    $old = CreateReservationHold::run(holdInput($user, $cabin));
    $old->update(['hold_expires_at' => now()->subHour()]);

    $next = CreateReservationHold::run(holdInput($user, $cabin));

    expect($next->id)->not->toBe($old->id);
});

test('active hold blocks another hold on same dates', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    CreateReservationHold::run(holdInput($user, $cabin));

    expect(fn () => CreateReservationHold::run(holdInput($user, $cabin)))->toThrow(ValidationException::class);
});

test('sequential double attempt only allows one hold', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    $first = CreateReservationHold::run(holdInput($user, $cabin));

    try {
        CreateReservationHold::run(holdInput($user, $cabin));
        $this->fail('Second hold should have been rejected.');
    } catch (ValidationException $e) {
        expect($e->getMessage())->not->toBeEmpty();
    }

    expect(Reservation::query()->active()->overlapping($cabin->id, '2026-10-10', '2026-10-12')->count())->toBe(1)
        ->and($first->booking_code)->toStartWith('WVS-');
});

test('booking codes are unique across holds', function () {
    $user = User::factory()->create();
    $cabinA = Cabin::factory()->create();
    $cabinB = Cabin::factory()->create();
    $a = CreateReservationHold::run(holdInput($user, $cabinA));
    $b = CreateReservationHold::run(holdInput($user, $cabinB));

    expect($a->booking_code)->not->toBe($b->booking_code)
        ->and($a->booking_code)->toMatch('/^WVS-[ABCDEFGHJKMNPQRSTUVWXYZ23456789]{8}$/');
});

test('expired pending reservation is not counted active', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();
    $old = CreateReservationHold::run(holdInput($user, $cabin));
    $old->update(['hold_expires_at' => now()->subMinutes(5)]);

    expect(Reservation::query()->active()->whereKey($old->id)->exists())->toBeFalse()
        ->and(Reservation::overlaps('2026-10-10', '2026-10-12', '2026-10-11', '2026-10-13'))->toBeTrue()
        ->and(Reservation::overlaps('2026-10-10', '2026-10-12', '2026-10-12', '2026-10-14'))->toBeFalse();
});
