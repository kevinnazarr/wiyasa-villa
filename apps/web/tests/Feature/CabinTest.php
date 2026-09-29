<?php

use App\Actions\Reservation\CreateReservationHold;
use App\Enums\CabinStatus;
use App\Models\Cabin;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

test('cabin can be created with defaults', function () {
    $cabin = Cabin::factory()->create();

    expect($cabin->capacity)->toBe(7)
        ->and($cabin->base_occupancy)->toBe(4)
        ->and($cabin->status)->toBe(CabinStatus::Active);
});

test('cabin slug is unique', function () {
    $cabin = Cabin::factory()->create(['slug' => 'wy-01']);

    expect(fn () => Cabin::factory()->create(['slug' => 'wy-01']))->toThrow(QueryException::class);
});

test('cabin code is unique', function () {
    Cabin::factory()->create(['code' => 'WY-01']);

    expect(fn () => Cabin::factory()->create(['code' => 'WY-01']))->toThrow(QueryException::class);
});

test('cabin has translations with unique locale per cabin', function () {
    $cabin = Cabin::factory()->withTranslations()->create();

    expect($cabin->translations)->toHaveCount(2)
        ->and(fn () => $cabin->translations()->create(['locale' => 'id', 'name' => 'dup']))->toThrow(QueryException::class);
});

test('cabin nameFor falls back id to en to canonical', function () {
    $cabin = Cabin::factory()->withTranslations()->create();
    $cabin->load('translations');

    expect($cabin->nameFor('id'))->toEndWith(' ID')
        ->and($cabin->nameFor('en'))->toEndWith(' EN')
        ->and($cabin->nameFor('fr'))->toEndWith(' EN');

    $bare = Cabin::factory()->create();

    expect($bare->nameFor('id'))->toBe($bare->name);
});

test('inactive cabin rejects holds', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->inactive()->create();

    expect(fn () => CreateReservationHold::run([
        'user_id' => $user->id,
        'cabin_id' => $cabin->id,
        'check_in' => '2026-11-10',
        'check_out' => '2026-11-12',
        'adults' => 2,
        'children' => 0,
        'infants' => 0,
        'total_guests' => 2,
    ]))->toThrow(ValidationException::class);
});

test('maintenance cabin rejects holds', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->maintenance()->create();

    expect(fn () => CreateReservationHold::run([
        'user_id' => $user->id,
        'cabin_id' => $cabin->id,
        'check_in' => '2026-11-10',
        'check_out' => '2026-11-12',
        'adults' => 2,
        'children' => 0,
        'infants' => 0,
        'total_guests' => 2,
    ]))->toThrow(ValidationException::class);
});

test('guest count over capacity is rejected', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create(['capacity' => 7]);

    expect(fn () => CreateReservationHold::run([
        'user_id' => $user->id,
        'cabin_id' => $cabin->id,
        'check_in' => '2026-11-10',
        'check_out' => '2026-11-12',
        'adults' => 5,
        'children' => 3,
        'infants' => 0,
        'total_guests' => 8,
    ]))->toThrow(ValidationException::class);
});

test('reservation belongs to cabin and cabin has reservations', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();

    $reservation = CreateReservationHold::run([
        'user_id' => $user->id,
        'cabin_id' => $cabin->id,
        'check_in' => '2026-11-10',
        'check_out' => '2026-11-12',
        'adults' => 2,
        'children' => 0,
        'infants' => 0,
        'total_guests' => 2,
    ]);

    expect($reservation->cabin->is($cabin))->toBeTrue()
        ->and($cabin->reservations()->whereKey($reservation->id)->exists())->toBeTrue();
});

test('foreign key blocks invalid cabin id', function () {
    $user = User::factory()->create();

    expect(fn () => CreateReservationHold::run([
        'user_id' => $user->id,
        'cabin_id' => 999999,
        'check_in' => '2026-11-10',
        'check_out' => '2026-11-12',
        'adults' => 2,
        'children' => 0,
        'infants' => 0,
        'total_guests' => 2,
    ]))->toThrow(Exception::class);
});
