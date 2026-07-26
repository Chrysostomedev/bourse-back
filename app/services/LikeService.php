<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Post;
use App\Models\Scholarship;
use App\Models\User;

class LikeService
{
    /**
     * Toggle : si l'utilisateur a déjà liké, on retire le like ; sinon
     * on le crée. Retourne l'état final + le nombre total de likes,
     * pratique pour que l'appli mette à jour le compteur en un aller-retour.
     */
    public function toggle(array $data, User $user): array
    {
        $likeableClass = $this->resolveMorphClass($data['likeable_type']);
        $likeableClass::query()->findOrFail($data['likeable_id']);

        $existing = Like::query()
            ->where('user_id', $user->id)
            ->where('likeable_type', $likeableClass)
            ->where('likeable_id', $data['likeable_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            $isLiked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'likeable_type' => $likeableClass,
                'likeable_id' => $data['likeable_id'],
            ]);
            $isLiked = true;
        }

        $totalLikes = Like::query()
            ->where('likeable_type', $likeableClass)
            ->where('likeable_id', $data['likeable_id'])
            ->count();

        return ['is_liked' => $isLiked, 'likes_count' => $totalLikes];
    }

    private function resolveMorphClass(string $alias): string
    {
        return match ($alias) {
            'scholarship' => Scholarship::class,
            'post' => Post::class,
        };
    }
}