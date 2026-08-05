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
            'id'              => $this->id,
            'title'           => $this->title,
            'slug'            => $this->slug,
            'organism_name'   => $this->organism_name,
            'country'         => new CountryResource($this->whenLoaded('country')),
            'study_level'     => $this->whenLoaded('studyLevel', fn () => $this->studyLevel?->name),
            'funding_type'    => $this->funding_type,
            'status'          => $this->status,
            'is_featured'     => $this->is_featured,
            'cover_image_url' => $this->cover_image ? Storage::url($this->cover_image) : null,

            // Date brute de la prochaine deadline (plus propre pour le front)
            'next_deadline'   => $this->nextDeadline()?->toIso8601String(),

            // Toujours utile pour l’affichage rapide
            'days_remaining'  => $this->daysRemainingUntilNextDeadline(),

            'created_at'      => $this->created_at?->toIso8601String(),
        ];
    }

    private function nextDeadline(): ?\Carbon\Carbon
    {
        if (! $this->relationLoaded('intakes')) {
            return null;
        }

        return $this->intakes
            ->pluck('period_end')
            ->filter()
            ->filter(fn ($date) => $date->isFuture())
            ->sort()
            ->first();
    }

    private function daysRemainingUntilNextDeadline(): ?int
    {
        $next = $this->nextDeadline();

        return $next ? now()->diffInDays($next, false) : null;
    }
}