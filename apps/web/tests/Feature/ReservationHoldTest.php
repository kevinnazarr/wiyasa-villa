<?php

use App\Actions\Reservation\CreateReservationHold;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Validation\ValidationException;

function holdInput(User $user, array $overrides = []): array
{
    return array_merge([
        'user_id' => $user->id,
        'cabin_id' => 1,
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

    $reservation = CreateReservationHold::run(holdInput($user));

    expect($reservation->status->value)->toBe('PENDING_PAYMENT')
        ->and($reservation->booking_code)->toStartWith('WVS-')
        ->and($reservation->hold_expires_at)->not->toBeNull();
});

test('overlapping reservation is rejected', function () {
    $user = User::factory()->create();
    CreateReservationHold::run(holdInput($user));

    expect(fn () => CreateReservationHold::run(holdInput($user, [
        'check_in' => '2026-10-11',
        'check_out' => '2026-10-13',
    ])))->toThrow(ValidationException::class);
});

test('adjacent stays are allowed', function () {
    $user = User::factory()->create();
    CreateReservationHold::run(holdInput($user));

    $next = CreateReservationHold::run(holdInput($user, [
        'check_in' => '2026-10-12',
        'check_out' => '2026-10-14',
    ]));

    expect($next->id)->toBeGreaterThan(0);
});

test('expired hold does not block new hold', function () {
    $user = User::factory()->create();
    $old = CreateReservationHold::run(holdInput($user));
    $old->update(['hold_expires_at' => now()->subHour()]);

    $next = CreateReservationHold::run(holdInput($user));

    expect($next->id)->not->toBe($old->id);
});

test('active hold blocks another hold on same dates', function () {
    $user = User::factory()->create();
    CreateReservationHold::run(holdInput($user));

    expect(fn () => CreateReservationHold::run(holdInput($user)))->toThrow(ValidationException::class);
});

test('sequential double attempt only allows one hold', function () {
    $user = User::factory()->create();
    $first = CreateReservationHold::run(holdInput($user));

    try {
        CreateReservationHold::run(holdInput($user));
        $this->fail('Second hold should have been rejected.');
    } catch (ValidationException $e) {
        expect($e->getMessage())->not->toBeEmpty();
    }

    expect(Reservation::query()->active()->overlapping(1, '2026-10-10', '2026-10-12')->count())->toBe(1)
        ->and($first->booking_code)->toStartWith('WVS-');
});

test('booking codes are unique across holds', function () {
    $user = User::factory()->create();
    $a = CreateReservationHold::run(holdInput($user, ['cabin_id' => 1]));
    $b = CreateReservationHold::run(holdInput($user, ['cabin_id' => 2]));

    expect($a->booking_code)->not->toBe($b->booking_code)
        ->and($a->booking_code)->toMatch('/^WVS-[ABCDEFGHJKMNPQRSTUVWXYZ23456789]{8}$/');
});

test('expired pending reservation is not counted active', function () {
    $user = User::factory()->create();
    $old = CreateReservationHold::run(holdInput($user));
    $old->update(['hold_expires_at' => now()->subMinutes(5)]);

    expect(Reservation::query()->active()->whereKey($old->id)->exists())->toBeFalse()
        ->and(Reservation::overlaps('2026-10-10', '2026-10-12', '2026-10-11', '2026-10-13'))->toBeTrue()
        ->and(Reservation::overlaps('2026-10-10', '2026-10-12', '2026-10-12', '2026-10-14'))->toBeFalse();
});
