<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description,
            'price' => $this->price,
            'cover_image_url' => $this->cover_image ? Storage::url($this->cover_image) : null,
            // file_url peut être un chemin storage local OU un lien
            // externe complet — on ne re-préfixe que s'il s'agit d'un
            // chemin local (ne commence pas par http).
            'file_url' => $this->file_url && ! Str::startsWith($this->file_url, ['http://', 'https://'])
                ? Storage::url($this->file_url)
                : $this->file_url,
            'is_active' => $this->is_active,
        ];
    }
}