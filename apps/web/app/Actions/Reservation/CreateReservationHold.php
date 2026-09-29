<?php

namespace App\Actions\Reservation;

use App\Enums\ReservationSource;
use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateReservationHold
{
    /**
     * @param  array<string, mixed>  $input
     */
    public static function run(array $input): Reservation
    {
        $checkIn = (string) ($input['check_in'] ?? '');
        $checkOut = (string) ($input['check_out'] ?? '');

        if ($checkOut <= $checkIn) {
            throw ValidationException::withMessages(['check_out_date' => 'Check-out must be after check-in.']);
        }

        $adults = (int) ($input['adults'] ?? 0);
        $children = (int) ($input['children'] ?? 0);
        $infants = (int) ($input['infants'] ?? 0);
        $total = (int) ($input['total_guests'] ?? ($adults + $children + $infants));

        if ($total !== $adults + $children + $infants) {
            throw ValidationException::withMessages(['total_guests' => 'Total guests must equal adults + children + infants.']);
        }

        $lastError = null;

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            try {
                return DB::transaction(function () use ($input, $checkIn, $checkOut, $adults, $children, $infants, $total) {
                    $cabinId = (int) $input['cabin_id'];

                    // ponytail: no cabin-row lock, cabins table is FUTURE; re-check overlap inside txn, add lockForUpdate on cabins when table exists
                    $conflict = Reservation::query()->active()->overlapping($cabinId, $checkIn, $checkOut)->exists();

                    if ($conflict) {
                        throw ValidationException::withMessages(['cabin_id' => 'Cabin is not available for the selected dates.']);
                    }

                    $holdMinutes = max(1, (int) config('booking.default_hold_minutes', 15));
                    $now = now();

                    return Reservation::create([
                        'user_id' => $input['user_id'],
                        'cabin_id' => $cabinId,
                        'booking_code' => self::generateCode(),
                        'locale' => $input['locale'] ?? 'id',
                        'source' => $input['source'] ?? ReservationSource::DirectWebsite,
                        'status' => ReservationStatus::PendingPayment,
                        'check_in_date' => $checkIn,
                        'check_out_date' => $checkOut,
                        'adults' => $adults,
                        'children' => $children,
                        'infants' => $infants,
                        'total_guests' => $total,
                        'currency' => $input['currency'] ?? 'IDR',
                        'subtotal_amount' => (int) ($input['subtotal_amount'] ?? 0),
                        'extra_guest_amount' => (int) ($input['extra_guest_amount'] ?? 0),
                        'discount_amount' => (int) ($input['discount_amount'] ?? 0),
                        'total_amount' => (int) ($input['total_amount'] ?? 0),
                        'hold_expires_at' => $now->copy()->addMinutes($holdMinutes),
                        'price_locked_at' => $now,
                        'cancellation_policy' => $input['cancellation_policy'] ?? null,
                    ]);
                });
            } catch (ValidationException $e) {
                throw $e;
            } catch (QueryException $e) {
                $lastError = $e;
            }
        }

        throw $lastError ?? ValidationException::withMessages(['booking_code' => 'Could not generate a unique booking code.']);
    }

    private static function generateCode(): string
    {
        $alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        $code = '';

        for ($i = 0; $i < 8; $i++) {
            $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return 'WVS-'.$code;
    }
}
