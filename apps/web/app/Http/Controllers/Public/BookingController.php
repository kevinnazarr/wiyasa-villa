<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\CabinResource;
use App\Models\Cabin;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /**
     * Render the booking form contract with the cabin catalog for selection.
     */
    public function index(Request $request): Response
    {
        $cabins = Cabin::query()
            ->where('status', 'ACTIVE')
            ->with('translations')
            ->orderBy('code')
            ->get();

        $requestedSlug = $request->query('cabin');
        $cabinSlug = is_string($requestedSlug) && $cabins->contains('slug', $requestedSlug)
            ? $requestedSlug
            : null;

        return Inertia::render('public/booking/index', [
            'cabins' => CabinResource::collection($cabins)->resolve($request),
            'cabinSlug' => $cabinSlug,
        ]);
    }

    /**
     * Render the booking confirmation contract looked up by public code.
     *
     * Reservation records do not exist yet, so only the requested code
     * is returned and the page renders its empty state.
     */
    public function confirmation(Request $request): Response
    {
        $code = $request->query('code');

        return Inertia::render('public/booking/confirmation', [
            'code' => is_string($code) ? $code : null,
            'reservation' => null,
        ]);
    }
}
