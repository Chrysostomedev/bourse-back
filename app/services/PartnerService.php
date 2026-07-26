<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PartnerService
{
    public function create(array $data): Partner
    {
        return Partner::create([
            'name' => $data['name'],
            'logo' => $this->storeLogo($data['logo'] ?? null),
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
        ]);
    }

    public function update(Partner $partner, array $data): Partner
    {
        $partner->update([
            'name' => $data['name'],
            'logo' => isset($data['logo'])
                ? $this->storeLogo($data['logo'], $partner->logo)
                : $partner->logo,
            'website' => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
        ]);

        return $partner;
    }

    public function delete(Partner $partner): void
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();
    }

    private function storeLogo(?UploadedFile $file, ?string $previousPath = null): ?string
    {
        if (! $file) {
            return $previousPath;
        }

        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return $file->store('partners', 'public');
    }
}