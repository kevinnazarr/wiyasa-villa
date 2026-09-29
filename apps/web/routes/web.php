<?php

use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\Public\CabinController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/id');

Route::prefix('{locale?}')->whereIn('locale', ['id', 'en'])->group(function () {
    Route::inertia('/', 'public/home/index')->name('home');
    Route::get('cabins', [CabinController::class, 'index'])->name('cabins.index');
    Route::get('cabins/{cabin}', [CabinController::class, 'show'])->name('cabins.show');
    Route::get('booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('booking', [BookingController::class, 'store'])
        ->middleware('auth')
        ->name('booking.store');
    Route::get('booking/confirmation', [BookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::inertia('about', 'public/about/index')->name('about');
    Route::inertia('contact', 'public/contact/index')->name('contact');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('bookings', [UserBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
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
