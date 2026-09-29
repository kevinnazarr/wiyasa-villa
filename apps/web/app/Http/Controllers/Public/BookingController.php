<?php

namespace App\Http\Controllers\Public;

use App\Actions\Reservation\CheckCabinAvailability;
use App\Actions\Reservation\CreateReservationHold;
use App\Enums\CabinStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Models\Cabin;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $queryId = $request->query('cabin_id');

        $cabinId = is_numeric($queryId)
            ? Cabin::query()->whereKey((int) $queryId)->where('status', CabinStatus::Active)->value('id')
            : null;

        $checkIn = (string) $request->query('check_in', '');
        $checkOut = (string) $request->query('check_out', '');

        if ($cabinId !== null && $checkIn !== '' && $checkOut !== '') {
            $guests = (int) $request->query('adults', 1)
                + (int) $request->query('children', 0)
                + (int) $request->query('infants', 0);

            $availability = CheckCabinAvailability::run($cabinId, $checkIn, $checkOut, $guests);

            if (! $availability->available) {
                return to_route('booking.index', ['cabin_id' => $cabinId])
                    ->withErrors(['cabin_id' => self::availabilityMessage($availability->reason ?? 'unavailable')]);
            }
        }

        $user = $request->user();

        return Inertia::render('public/booking/index', [
            'cabinId' => $cabinId,
            'authUser' => $user !== null ? [
                'name' => $user->name,
                'email' => $user->email,
            ] : null,
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $reservation = CreateReservationHold::run([
            ...$validated,
            'user_id' => $user?->id,
            'guest_name' => $validated['guest_name'] ?? $user?->name,
            'guest_email' => $validated['guest_email'] ?? $user?->email,
            'guest_phone' => $validated['guest_phone'] ?? null,
            'locale' => $request->route('locale') ?? app()->getLocale(),
        ]);

        return to_route('booking.confirmation', ['token' => $reservation->public_token]);
    }

    public function confirmation(Request $request): Response
    {
        $token = (string) $request->query('token', '');

        $reservation = $token !== ''
            ? Reservation::query()->where('public_token', $token)->first()
            : null;

        return Inertia::render('public/booking/confirmation', [
            'reservation' => $reservation !== null ? [
                'code' => $reservation->booking_code,
                'status' => $reservation->status->value,
            ] : null,
        ]);
    }

    private static function availabilityMessage(string $reason): string
    {
        return match ($reason) {
            'invalid_dates' => 'Check-out must be after check-in.',
            'invalid_cabin' => 'Selected cabin does not exist.',
            'unavailable_status' => 'Cabin is not available for booking.',
            'over_capacity' => 'Guest count exceeds cabin capacity.',
            'date_conflict' => 'Cabin is not available for the selected dates.',
            default => 'Cabin is not available for the selected dates.',
        };
    }
}
