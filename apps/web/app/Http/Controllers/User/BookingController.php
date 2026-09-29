<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function index(Request $request): Response
    {
        $bookings = Reservation::query()
            ->where('user_id', $request->user()->id)
            ->with('cabin')
            ->latest()
            ->get()
            ->map(fn (Reservation $reservation) => [
                'id' => $reservation->id,
                'code' => $reservation->booking_code,
                'status' => $reservation->status->value,
            ]);

        return Inertia::render('user/bookings/index', [
            'bookings' => $bookings,
        ]);
    }

    public function show(Request $request): Response
    {
        $reservation = Reservation::query()
            ->where('user_id', $request->user()->id)
            ->whereKey((string) $request->route('booking'))
            ->firstOrFail();

        return Inertia::render('user/bookings/show', [
            'booking' => ['id' => $reservation->id],
        ]);
    }
}
