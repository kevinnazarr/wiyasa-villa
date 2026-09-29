<?php

namespace App\Models;

use App\Enums\ReservationSource;
use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $cabin_id
 * @property string $booking_code
 * @property string $locale
 * @property ReservationSource $source
 * @property ReservationStatus $status
 * @property Cabin $cabin
 */
class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'cabin_id',
        'booking_code',
        'locale',
        'source',
        'status',
        'check_in_date',
        'check_out_date',
        'adults',
        'children',
        'infants',
        'total_guests',
        'currency',
        'subtotal_amount',
        'extra_guest_amount',
        'discount_amount',
        'total_amount',
        'hold_expires_at',
        'price_locked_at',
        'cancellation_policy',
    ];

    protected function casts(): array
    {
        return [
            'source' => ReservationSource::class,
            'status' => ReservationStatus::class,
            'check_in_date' => 'immutable_date',
            'check_out_date' => 'immutable_date',
            'hold_expires_at' => 'immutable_datetime',
            'price_locked_at' => 'immutable_datetime',
            'cabin_id' => 'integer',
            'adults' => 'integer',
            'children' => 'integer',
            'infants' => 'integer',
            'total_guests' => 'integer',
            'subtotal_amount' => 'integer',
            'extra_guest_amount' => 'integer',
            'discount_amount' => 'integer',
            'total_amount' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cabin(): BelongsTo
    {
        return $this->belongsTo(Cabin::class);
    }

    /**
     * @param  Builder<Reservation>  $query
     * @return Builder<Reservation>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $q): void {
            $q->whereIn('status', [ReservationStatus::Confirmed, ReservationStatus::CheckedIn])
                ->orWhere(function (Builder $q2): void {
                    $q2->where('status', ReservationStatus::PendingPayment)
                        ->where('hold_expires_at', '>', now());
                });
        });
    }

    /**
     * @param  Builder<Reservation>  $query
     * @return Builder<Reservation>
     */
    public function scopeOverlapping(Builder $query, int $cabinId, string $checkIn, string $checkOut): Builder
    {
        // whereDate: sqlite stores date casts with a time component, so compare date parts only.
        return $query->where('cabin_id', $cabinId)
            ->whereDate('check_in_date', '<', $checkOut)
            ->whereDate('check_out_date', '>', $checkIn);
    }

    public static function overlaps(string $existingIn, string $existingOut, string $requestedIn, string $requestedOut): bool
    {
        return $existingIn < $requestedOut && $existingOut > $requestedIn;
    }
}
