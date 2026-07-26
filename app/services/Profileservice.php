<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function update(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? $user->phone,
            'avatar' => isset($data['avatar'])
                ? $this->storeAvatar($data['avatar'], $user->avatar)
                : $user->avatar,
        ]);

        return $user;
    }

    private function storeAvatar(?UploadedFile $file, ?string $previousPath = null): ?string
    {
        if (! $file) {
            return $previousPath;
        }

        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return $file->store('avatars', 'public');
    }
}