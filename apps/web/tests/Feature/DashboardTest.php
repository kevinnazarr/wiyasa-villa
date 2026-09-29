<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('bookings.index', ['locale' => 'id']));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit their bookings', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('bookings.index', ['locale' => 'id']));
    $response->assertOk();
});

test('customer dashboard route no longer exists', function () {
    $this->assertFalse(Route::has('dashboard'));
});
