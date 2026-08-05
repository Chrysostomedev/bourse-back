<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CommentService
{
    /**
     * Crée un nouveau commentaire
     */
    public function store(array $data, User $user): Comment
    {
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->content = $data['content'];
        
        // Polymorphe : commentable_type et commentable_id
        if (isset($data['commentable_type']) && isset($data['commentable_id'])) {
            $comment->commentable_type = $data['commentable_type'];
            $comment->commentable_id = $data['commentable_id'];
        }
        
        $comment->parent_id = $data['parent_id'] ?? null;
        $comment->save();
        
        return $comment;
    }

    /**
     * Supprime un commentaire (si auteur ou admin)
     */
    public function delete(Comment $comment, User $user): void
    {
        // Vérifier que l'utilisateur est l'auteur ou admin
        if ($comment->user_id !== $user->id && !$user->is_admin) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }
        
        $comment->delete();
    }

    /**
     * Met à jour un commentaire (si auteur)
     */
    public function update(Comment $comment, array $data, User $user): Comment
    {
        if ($comment->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce commentaire.');
        }
        
        $comment->content = $data['content'];
        $comment->save();
        
        return $comment;
    }
}
