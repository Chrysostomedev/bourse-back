<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ServiceOfferingService
{
    public function create(array $data): Service
    {
        return Service::create([
            'title' => $data['title'],
            'kind' => $data['kind'],
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $this->storeImage($data['image'] ?? null),
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update([
            'title' => $data['title'],
            'kind' => $data['kind'],
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => isset($data['image'])
                ? $this->storeImage($data['image'], $service->image)
                : $service->image,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $service;
    }

    public function delete(Service $service): void
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();
    }

    private function storeImage(?UploadedFile $file, ?string $previousPath = null): ?string
    {
        if (! $file) {
            return $previousPath;
        }

        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return $file->store('services', 'public');
    }
}