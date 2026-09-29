<?php

namespace App\Models;

use App\Enums\CabinStatus;
use Database\Factories\CabinFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $capacity
 * @property int $base_occupancy
 * @property CabinStatus $status
 */
class Cabin extends Model
{
    /** @use HasFactory<CabinFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
        'capacity',
        'base_occupancy',
        'status',
        'check_in_time',
        'check_out_time',
    ];

    protected function casts(): array
    {
        return [
            'status' => CabinStatus::class,
            'capacity' => 'integer',
            'base_occupancy' => 'integer',
        ];
    }

    /**
     * @return HasMany<CabinTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CabinTranslation::class);
    }

    /**
     * @return HasMany<Reservation, $this>
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function nameFor(string $locale): ?string
    {
        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        return $translations->firstWhere('locale', $locale)?->name
            ?? $translations->firstWhere('locale', 'en')?->name
            ?? $this->name;
    }
}
