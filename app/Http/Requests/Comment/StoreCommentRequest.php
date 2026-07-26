<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'authentification (auth:sanctum) est déjà garantie par le
        // middleware de route — ici on ne valide que la forme.
        return true;    
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:1000'],

            // "scholarship" ou "post" — alias courts définis dans le
            // morphMap (AppServiceProvider), plutôt que le nom de
            // classe complet App\Models\Scholarship exposé à l'appli.
            'commentable_type' => ['required', Rule::in(['scholarship', 'post'])],
            'commentable_id' => ['required', 'integer'],

            // Réponse à un commentaire existant (fil à 1 niveau)
            'parent_id' => ['nullable', 'exists:comments,id'],
        ];
    }
}