<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ScholarshipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'organism_name' => $this->organism_name,
            'country' => new CountryResource($this->whenLoaded('country')),
            'funding_type' => $this->funding_type,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'cover_image_url' => $this->cover_image ? Storage::url($this->cover_image) : null,

            // Calculé à la volée, jamais stocké en base — évite qu'un
            // compteur devienne faux si personne ne relance de job.
            'days_remaining' => $this->daysRemainingUntilNextDeadline(),

            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    /**
     * Regarde la période de candidature la plus proche dans le futur
     * parmi toutes les intakes de la bourse (si déjà chargées).
     */
    private function daysRemainingUntilNextDeadline(): ?int
    {
        if (! $this->relationLoaded('intakes')) {
            return null;
        }

        $nextDeadline = $this->intakes
            ->pluck('period_end')
            ->filter()
            ->filter(fn ($date) => $date->isFuture())
            ->sort()
            ->first();

        return $nextDeadline ? now()->diffInDays($nextDeadline, false) : null;
    }
}