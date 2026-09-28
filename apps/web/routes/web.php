<?php

use App\Http\Controllers\Public\BookingController as PublicBookingController;
use App\Http\Controllers\Public\CabinController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/id');

Route::prefix('{locale?}')->whereIn('locale', ['id', 'en'])->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('cabins', [CabinController::class, 'index'])->name('cabins.index');
    Route::get('cabins/{cabin:slug}', [CabinController::class, 'show'])->name('cabins.show');
    Route::get('booking', [PublicBookingController::class, 'index'])->name('booking.index');
    Route::get('booking/confirmation', [PublicBookingController::class, 'confirmation'])->name('booking.confirmation');
    Route::get('about', [PageController::class, 'about'])->name('about');
    Route::get('contact', [PageController::class, 'contact'])->name('contact');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('bookings', [UserBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{code}', [UserBookingController::class, 'show'])->name('bookings.show');
        Route::get('profile', [UserProfileController::class, 'show'])->name('profile.show');
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
