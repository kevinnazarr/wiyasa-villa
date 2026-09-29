<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $cabin_id
 * @property string $locale
 * @property string $name
 * @property string|null $description
 */
class CabinTranslation extends Model
{
    protected $fillable = [
        'cabin_id',
        'locale',
        'name',
        'description',
    ];

    /**
     * @return BelongsTo<Cabin, $this>
     */
    public function cabin(): BelongsTo
    {
        return $this->belongsTo(Cabin::class);
    }
}
