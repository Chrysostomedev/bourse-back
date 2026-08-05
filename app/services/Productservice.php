<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function create(array $data): Product
    {
        return Product::create([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'image' => $this->storeImage($data['image'] ?? null),
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
            'image' => isset($data['image'])
                ? $this->storeImage($data['image'], $product->image)
                : $product->image,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
    }

    private function storeImage(?UploadedFile $file, ?string $previousPath = null): ?string
    {
        if (! $file) {
            return $previousPath;
        }

        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return $file->store('products/images', 'public');
    }
}
