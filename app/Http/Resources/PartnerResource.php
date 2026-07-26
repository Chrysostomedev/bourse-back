<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PartnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo_url' => $this->logo ? Storage::url($this->logo) : null,
            'website' => $this->website,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
        ];
    }
}