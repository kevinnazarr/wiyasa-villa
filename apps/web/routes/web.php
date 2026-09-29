<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/id');

Route::prefix('{locale?}')->whereIn('locale', ['id', 'en'])->group(function () {
    Route::inertia('/', 'public/home/index')->name('home');
    Route::inertia('cabins', 'public/cabins/index')->name('cabins.index');
    Route::inertia('cabins/{cabin}', 'public/cabins/show')->name('cabins.show');
    Route::inertia('booking', 'public/booking/index')->name('booking.index');
    Route::inertia('booking/confirmation', 'public/booking/confirmation')->name('booking.confirmation');
    Route::inertia('about', 'public/about/index')->name('about');
    Route::inertia('contact', 'public/contact/index')->name('contact');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::inertia('bookings', 'user/bookings/index')->name('bookings.index');
        Route::inertia('bookings/{booking}', 'user/bookings/show')->name('bookings.show');
        Route::inertia('profile', 'user/profile/index')->name('profile.index');
    });

    require __DIR__.'/settings.php';
});

Route::fallback(function () {
    $segments = request()->segments();
    $firstSegment = $segments[0] ?? '';

    if (! in_array($firstSegment, ['id', 'en'], true)) {
        array_unshift($segments, 'id');

        return redirect()->to(implode('/', $segments));
    }

    abort(404);
});
