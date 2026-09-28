<?php

use App\Models\Cabin;
use App\Models\User;

dataset('public locales', ['id', 'en']);

test('home page renders for each locale', function (string $locale) {
    $response = $this->get("/{$locale}");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/home/index')
        ->where('locale', $locale)
    );
})->with('public locales');

test('cabins index page renders active cabins for each locale', function (string $locale) {
    $cabin = Cabin::factory()->create();

    $response = $this->get("/{$locale}/cabins");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/cabins/index')
        ->where('locale', $locale)
        ->has('cabins', 1)
        ->where('cabins.0.slug', $cabin->slug)
    );
})->with('public locales');

test('cabin show page resolves by slug for each locale', function (string $locale) {
    $cabin = Cabin::factory()->create(['slug' => "pine-ridge-{$locale}"]);

    $response = $this->get("/{$locale}/cabins/{$cabin->slug}");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/cabins/show')
        ->where('locale', $locale)
        ->where('cabin.slug', $cabin->slug)
    );
})->with('public locales');

test('unknown cabin slug returns 404', function () {
    $response = $this->get('/id/cabins/unknown-slug');

    $response->assertNotFound();
});

test('booking page renders cabin catalog for each locale', function (string $locale) {
    Cabin::factory()->create();

    $response = $this->get("/{$locale}/booking");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/booking/index')
        ->where('locale', $locale)
        ->has('cabins', 1)
    );
})->with('public locales');

test('booking confirmation page renders for each locale', function (string $locale) {
    $response = $this->get("/{$locale}/booking/confirmation");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/booking/confirmation')
        ->where('locale', $locale)
    );
})->with('public locales');

test('about page renders for each locale', function (string $locale) {
    $response = $this->get("/{$locale}/about");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/about/index')
        ->where('locale', $locale)
    );
})->with('public locales');

test('contact page renders for each locale', function (string $locale) {
    $response = $this->get("/{$locale}/contact");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/contact/index')
        ->where('locale', $locale)
    );
})->with('public locales');

test('customer dashboard renders for authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/id/dashboard');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('user/dashboard/index')
        ->where('locale', 'id')
    );
});

test('customer bookings index renders for authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/id/bookings');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('user/bookings/index')
        ->where('locale', 'id')
    );
});

test('customer booking detail renders not-found contract', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/id/bookings/WY-TEST-001');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('user/bookings/show')
        ->where('locale', 'id')
        ->where('code', 'WY-TEST-001')
        ->where('booking', null)
    );
});

test('customer profile renders for authenticated user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/id/profile');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('user/profile/index')
        ->where('locale', 'id')
    );
});

test('guests are redirected to login for customer pages', function (string $path) {
    $response = $this->get($path);

    $response->assertRedirect(route('login'));
})->with([
    '/id/dashboard',
    '/id/bookings',
    '/id/bookings/WY-TEST-001',
    '/id/profile',
]);
