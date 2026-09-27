<?php

use App\Models\User;

test('root path redirects to default id locale', function () {
    $response = $this->get('/');

    $response->assertRedirect('/id');
});

test('id locale route is accessible and sets locale to id', function () {
    $response = $this->get('/id');

    $response->assertOk();
    expect(app()->getLocale())->toBe('id');
    $response->assertInertia(fn ($page) => $page
        ->component('Welcome')
        ->where('locale', 'id')
    );
});

test('en locale route is accessible and sets locale to en', function () {
    $response = $this->get('/en');

    $response->assertOk();
    expect(app()->getLocale())->toBe('en');
    $response->assertInertia(fn ($page) => $page
        ->component('Welcome')
        ->where('locale', 'en')
    );
});

test('unsupported locale prefix redirects to default id locale with path preserved', function () {
    $response = $this->get('/fr/dashboard');

    $response->assertRedirect('/id/fr/dashboard');
});

test('missing locale prefix on deep path redirects to id prefix', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/id/dashboard');
});

test('authenticated routes respect locale prefix', function () {
    $user = User::factory()->make();

    $response = $this->actingAs($user)->get('/en/dashboard');

    $response->assertOk();
    expect(app()->getLocale())->toBe('en');
    $response->assertInertia(fn ($page) => $page
        ->component('Dashboard')
        ->where('locale', 'en')
    );
});
