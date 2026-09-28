<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /**
     * List the authenticated customer's bookings contract.
     *
     * Reservation records do not exist yet, so an empty
     * collection is returned with the real auth contract.
     */
    public function index(): Response
    {
        return Inertia::render('user/bookings/index', [
            'bookings' => [],
        ]);
    }

    /**
     * Show a single booking contract looked up by public code.
     *
     * Reservation records do not exist yet, so the page
     * renders its not-found state for any code. The code is
     * read from the route because the optional {locale?}
     * prefix shifts positional controller arguments.
     */
    public function show(Request $request): Response
    {
        $code = $request->route('code');

        return Inertia::render('user/bookings/show', [
            'code' => is_string($code) ? $code : null,
            'booking' => null,
        ]);
    }
}
