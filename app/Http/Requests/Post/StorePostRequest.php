<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            // Lien externe (YouTube...) OU vidéo hébergée : on valide
            // juste que c'est une URL, le stockage d'un fichier vidéo
            // se ferait via un endpoint dédié si besoin plus tard.
            'video_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:brouillon,publie,archive'],
        ];
    }
}