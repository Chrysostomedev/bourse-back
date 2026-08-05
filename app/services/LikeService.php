<?php

namespace App\Services;

use App\Models\Like;
use App\Models\User;

class LikeService
{
    /**
     * Toggle like/unlike sur un élément polymorphe
     * 
     * @param array $data ['likeable_type' => 'App\\Models\\Post', 'likeable_id' => 1]
     * @param User $user
     * @return array ['is_liked' => bool, 'likes_count' => int]
     */
    public function toggle(array $data, User $user): array
    {
        $likeableType = $data['likeable_type'];
        $likeableId = $data['likeable_id'];
        
        // Chercher si l'utilisateur a déjà liké
        $existingLike = Like::where('user_id', $user->id)
            ->where('likeable_type', $likeableType)
            ->where('likeable_id', $likeableId)
            ->first();
        
        $isLiked = false;
        
        if ($existingLike) {
            // Already liked → unlike
            $existingLike->delete();
            $isLiked = false;
        } else {
            // Not liked → like
            Like::create([
                'user_id' => $user->id,
                'likeable_type' => $likeableType,
                'likeable_id' => $likeableId,
            ]);
            $isLiked = true;
        }
        
        // Compter les likes actuels
        $likesCount = Like::where('likeable_type', $likeableType)
            ->where('likeable_id', $likeableId)
            ->count();
        
        return [
            'is_liked' => $isLiked,
            'likes_count' => $likesCount,
        ];
    }
}
