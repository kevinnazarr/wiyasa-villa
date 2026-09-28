<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/id');

Route::prefix('{locale?}')->whereIn('locale', ['id', 'en'])->group(function () {
    Route::inertia('/', 'public/home/index')->name('home');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::inertia('dashboard', 'user/dashboard/index')->name('dashboard');
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
