<?php

namespace App\Http\Resources;

use App\Models\Cabin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Customer-facing cabin contract shared with Inertia pages.
 *
 * @mixin Cabin
 */
class CabinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'code' => $this->code,
            'slug' => $this->slug,
            'name' => $this->displayName($locale),
            'description' => $this->displayDescription($locale),
            'capacity' => $this->capacity,
            'base_occupancy' => $this->base_occupancy,
            'status' => $this->status,
        ];
    }
}
