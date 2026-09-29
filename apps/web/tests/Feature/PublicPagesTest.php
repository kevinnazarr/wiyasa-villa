<?php

use App\Models\Cabin;

dataset('public locales', ['id', 'en']);

test('home page renders for each locale', function (string $locale) {
    $response = $this->get("/{$locale}");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/home/index')
        ->where('locale', $locale)
    );
})->with('public locales');

test('cabins index page renders for each locale', function (string $locale) {
    $response = $this->get("/{$locale}/cabins");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/cabins/index')
        ->where('locale', $locale)
    );
})->with('public locales');

test('cabin show page renders for each locale', function (string $locale) {
    Cabin::factory()->create(['slug' => 'pine-ridge']);

    $response = $this->get("/{$locale}/cabins/pine-ridge");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/cabins/show')
        ->where('locale', $locale)
    );
})->with('public locales');

test('booking page renders for each locale', function (string $locale) {
    $response = $this->get("/{$locale}/booking");

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('public/booking/index')
        ->where('locale', $locale)
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
