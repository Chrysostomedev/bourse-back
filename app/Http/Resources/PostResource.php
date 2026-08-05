<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isLiked = false;
        
        // Si l'utilisateur est authentifié, vérifier s'il a liké ce post
        if ($request->user()) {
            $isLiked = $this->likes()
                ->where('user_id', $request->user()->id)
                ->exists();
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'cover_image_url' => $this->cover_image ? Storage::url($this->cover_image) : null,
            'video_url' => $this->video_url,
            'author' => new UserResource($this->whenLoaded('author')),
            'status' => $this->status,
            'views_count' => $this->views_count,
            'likes_count' => $this->whenCounted('likes'),
            'comments_count' => $this->whenCounted('comments'),
            'is_liked' => $isLiked,  // ← NOUVEAU : important pour le mobile
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}