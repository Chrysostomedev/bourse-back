<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'avatar_url' => $this->avatar ? Storage::url($this->avatar) : null,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),

            // password, otp_code, otp_expires_at, otp_type ne sont
            // JAMAIS listés ici — une Resource n'expose que ce qu'on
            // écrit explicitement dans ce tableau, contrairement à un
            // simple `return $user` qui renverrait tout le modèle.
        ];
    }
}