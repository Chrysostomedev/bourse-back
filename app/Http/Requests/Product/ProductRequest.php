<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'category' => ['required', 'in:ebook,guide_pdf,modele_lettre,autre'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
            // Fichier à héberger (PDF...) OU lien externe. Pas de
            // required_without ici : sur une mise à jour, l'admin ne
            // renvoie souvent ni l'un ni l'autre (il garde l'existant) —
            // la contrainte "au moins un des deux" est vérifiée dans
            // ProductService::create() uniquement, pas ici.
            'file' => ['nullable', 'file', 'mimes:pdf,epub', 'max:10240'],
            'external_link' => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }
}