<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Render the authenticated customer dashboard contract.
     *
     * Reservation records do not exist yet, so empty-state
     * placeholders are returned with the real user contract.
     */
    public function index(Request $request): Response
    {
        return Inertia::render('user/dashboard/index', [
            'user' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'upcomingBookings' => [],
            'recentBookings' => [],
        ]);
    }
}
