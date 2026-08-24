<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    /**
     * Génère un nouveau code à 6 chiffres pour l'utilisateur donné,
     * l'enregistre directement sur la ligne `users` (colonnes
     * otp_code / otp_expires_at / otp_type) et l'envoie par email.
     */
    public function generate(User $user, string $type): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->forceFill([
            'otp_code' => $code,
            'otp_expires_at' => now()->addMinutes(10),
            'otp_type' => $type,
        ])->save();

        Mail::to($user->email)->send(new OtpMail($code, $type));

        return $code;
    }

    /**
     * Vérifie le code fourni : bon type, pas expiré, correspond.
     * Si valide, on consomme immédiatement le code (il ne doit jamais
     * pouvoir être réutilisé, même dans les 10 minutes restantes).
     */
    public function verify(User $user, string $code, string $type): bool
    {
        $isValid = $user->otp_type === $type
            && $user->otp_code === $code
            && $user->otp_expires_at !== null
            && $user->otp_expires_at->isFuture();

        if ($isValid) {
            $this->clear($user);
        }

        return $isValid;
    }

    /**
     * Invalide le code courant — appelé après une vérification réussie,
     * ou pour annuler un code déjà envoyé avant d'en générer un nouveau.
     */
    public function clear(User $user): void
    {
        $user->forceFill([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_type' => null,
        ])->save();
    }
}