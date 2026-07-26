<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'kind' => $this->kind,
            'description' => $this->description,
            'price' => $this->price,
            'image_url' => $this->image ? Storage::url($this->image) : null,
            'is_active' => $this->is_active,
        ];
    }
}