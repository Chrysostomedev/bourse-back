<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'author' => new UserResource($this->whenLoaded('user')),
            'parent_id' => $this->parent_id,
            // Chargées uniquement si le controller a fait
            // ->load('replies') — évite une requête récursive inutile
            // sur un simple fil plat.
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}