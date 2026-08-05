<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ScholarshipDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,

            'organism_name' => $this->organism_name,
            'organism_logo_url' => $this->organism_logo ? Storage::url($this->organism_logo) : null,

            'country' => new CountryResource($this->whenLoaded('country')),
            'scholarship_type' => new ScholarshipTypeResource($this->whenLoaded('scholarshipType')),

            'funding_type' => $this->funding_type,
            'objective' => $this->objective,
            'conditions' => $this->conditions,
            'advantages' => $this->advantages,
            'additional_info' => $this->additional_info ?? [],
            'official_link' => $this->official_link,

            'cover_image_url' => $this->cover_image ? Storage::url($this->cover_image) : null,

            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'views_count' => $this->views_count,

            'study_levels' => new StudyLevelResource($this->whenLoaded('studyLevel')),
            'fields_of_study' => FieldOfStudyResource::collection($this->whenLoaded('fieldsOfStudy')),

            'intakes' => $this->whenLoaded('intakes', fn () => $this->intakes->map(fn ($intake) => [
                'id' => $intake->id,
                'intake_label' => $intake->intake_label,
                'period_start' => $intake->period_start?->toDateString(),
                'period_end' => $intake->period_end?->toDateString(),
                'period_label_text' => $intake->period_label_text,
            ])),

            // Champ utile uniquement côté admin (l'auteur du contenu) —
            // reste inoffensif à exposer côté public, mais on pourrait
            // le retirer avec un Resource séparé si besoin plus tard.
            'created_by' => $this->created_by,

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}