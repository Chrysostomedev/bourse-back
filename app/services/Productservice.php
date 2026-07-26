<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductService
{
    public function create(array $data): Product
    {
        // Le produit doit pointer vers quelque chose de téléchargeable :
        // un fichier interne OU un lien externe, au moins l'un des deux.
        if (empty($data['file']) && empty($data['external_link'])) {
            throw ValidationException::withMessages([
                'file' => ["Ajoute un fichier ou un lien externe."],
            ]);
        }

        return Product::create([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'cover_image' => $this->storeCover($data['cover_image'] ?? null),
            'file_url' => $this->resolveFileUrl($data),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'cover_image' => isset($data['cover_image'])
                ? $this->storeCover($data['cover_image'], $product->cover_image)
                : $product->cover_image,
            'file_url' => $this->resolveFileUrl($data, $product->file_url),
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }

        $product->delete();
    }

    /**
     * Priorité au fichier uploadé s'il est présent ; sinon au lien
     * externe fourni ; sinon on garde la valeur précédente (update).
     */
    private function resolveFileUrl(array $data, ?string $previous = null): ?string
    {
        if (! empty($data['file'])) {
            return $data['file']->store('products/files', 'public');
        }

        if (! empty($data['external_link'])) {
            return $data['external_link'];
        }

        return $previous;
    }

    private function storeCover(?UploadedFile $file, ?string $previousPath = null): ?string
    {
        if (! $file) {
            return $previousPath;
        }

        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return $file->store('products/covers', 'public');
    }
}