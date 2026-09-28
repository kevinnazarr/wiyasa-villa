<?php

namespace App\Models;

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
 * @property string $status
 * @property string|null $check_in_time
 * @property string|null $check_out_time
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

    protected $attributes = [
        'status' => 'ACTIVE',
    ];

    /**
     * Use slug for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return HasMany<CabinTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(CabinTranslation::class);
    }

    /**
     * Resolve the display name for a locale with fallback to canonical name.
     */
    public function displayName(string $locale, string $fallback = 'id'): string
    {
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', $fallback);

        return $translation?->name ?? $this->name;
    }

    /**
     * Resolve the display description for a locale with fallback to canonical description.
     */
    public function displayDescription(string $locale, string $fallback = 'id'): ?string
    {
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', $fallback);

        return $translation?->description ?? $this->description;
    }
}
