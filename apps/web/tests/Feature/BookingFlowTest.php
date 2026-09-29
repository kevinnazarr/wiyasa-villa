<?php

use App\Actions\Reservation\CreateReservationHold;
use App\Models\Cabin;
use App\Models\Reservation;
use App\Models\User;

function bookingPayload(Cabin $cabin, array $overrides = []): array
{
    return array_merge([
        'cabin_id' => $cabin->id,
        'check_in' => '2026-10-10',
        'check_out' => '2026-10-12',
        'adults' => 2,
        'children' => 0,
        'infants' => 0,
        'total_guests' => 2,
    ], $overrides);
}

test('public catalog lists only active cabins', function () {
    $active = Cabin::factory()->create();
    Cabin::factory()->inactive()->create();

    $response = $this->get('/id/cabins');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/cabins/index')
        ->has('cabins', 1)
        ->where('cabins.0.id', $active->id)
        ->where('cabins.0.name', $active->nameFor('id'))
    );
});

test('cabin detail renders active cabin and 404s on inactive or missing', function () {
    $active = Cabin::factory()->create();
    $inactive = Cabin::factory()->inactive()->create();

    $this->get("/id/cabins/{$active->slug}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('public/cabins/show')
            ->where('cabin.id', $active->id)
            ->where('cabin.name', $active->nameFor('id'))
        );

    $this->get("/id/cabins/{$active->id}")->assertOk();

    $this->get("/id/cabins/{$inactive->slug}")->assertNotFound();
    $this->get('/id/cabins/missing-slug')->assertNotFound();
});

test('booking post creates pending hold via engine', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();

    $response = $this->actingAs($user)->from('/id/booking')->post('/id/booking', bookingPayload($cabin));

    $reservation = Reservation::query()->where('cabin_id', $cabin->id)->sole();

    expect($reservation->status->value)->toBe('PENDING_PAYMENT')
        ->and($reservation->user_id)->toBe($user->id);

    $response->assertRedirectToRoute('booking.confirmation', ['token' => $reservation->public_token]);

    $this->actingAs($user)->get("/id/booking/confirmation?token={$reservation->public_token}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('public/booking/confirmation')
            ->where('reservation.code', $reservation->booking_code)
            ->where('reservation.status', 'PENDING_PAYMENT')
        );
});

test('double booking post on same dates conflicts', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();

    $this->actingAs($user)->from('/id/booking')->post('/id/booking', bookingPayload($cabin))->assertRedirect();

    $this->actingAs($user)->from('/id/booking')->post('/id/booking', bookingPayload($cabin))->assertSessionHasErrors();

    expect(Reservation::query()->where('cabin_id', $cabin->id)->count())->toBe(1);
});

test('bookings index is scoped to owner', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $cabin = Cabin::factory()->create();

    $mine = CreateReservationHold::run(array_merge(bookingPayload($cabin), ['user_id' => $owner->id]));
    CreateReservationHold::run(array_merge(
        bookingPayload($cabin, ['check_in' => '2026-10-20', 'check_out' => '2026-10-22']),
        ['user_id' => $other->id]
    ));

    $this->actingAs($owner)->get('/id/bookings')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user/bookings/index')
            ->has('bookings', 1)
            ->where('bookings.0.code', $mine->booking_code)
        );
});

test('booking detail is visible to owner only', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $cabin = Cabin::factory()->create();

    $mine = CreateReservationHold::run(array_merge(bookingPayload($cabin), ['user_id' => $owner->id]));

    $this->actingAs($owner)->get("/id/bookings/{$mine->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('user/bookings/show')
            ->where('booking.id', $mine->id)
        );

    $this->actingAs($other)->get("/id/bookings/{$mine->id}")->assertNotFound();
});

test('guests can create hold without login, bookings stay owner-scoped', function () {
    $cabin = Cabin::factory()->create();

    $response = $this->from('/id/booking')->post('/id/booking', array_merge(bookingPayload($cabin), [
        'guest_name' => 'Tamu Villa',
        'guest_email' => 'tamu@example.com',
    ]));

    $reservation = Reservation::query()->where('cabin_id', $cabin->id)->sole();

    expect($reservation->user_id)->toBeNull()
        ->and($reservation->guest_email)->toBe('tamu@example.com')
        ->and($reservation->public_token)->not->toBeNull();

    $response->assertRedirectToRoute('booking.confirmation', ['token' => $reservation->public_token]);
});

test('confirmation requires token, booking code is not enough', function () {
    $user = User::factory()->create();
    $cabin = Cabin::factory()->create();

    $reservation = CreateReservationHold::run(array_merge(bookingPayload($cabin), ['user_id' => $user->id]));

    $this->get("/id/booking/confirmation?code={$reservation->booking_code}")->assertOk()
        ->assertInertia(fn ($page) => $page->where('reservation', null));

    $this->get("/id/booking/confirmation?token={$reservation->public_token}")->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('reservation.code', $reservation->booking_code)
            ->where('reservation.status', 'PENDING_PAYMENT')
        );

    $this->get('/id/booking/confirmation?token=wrong-token')->assertOk()
        ->assertInertia(fn ($page) => $page->where('reservation', null));
});

test('guests are redirected to login for bookings index only', function () {
    $this->get('/id/bookings')->assertRedirect(route('login'));
    $this->get('/id/bookings/1')->assertRedirect(route('login'));
    $this->post('/id/booking', [])->assertSessionHasErrors();
});
