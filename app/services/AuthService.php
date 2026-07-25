<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Crée un compte utilisateur lambda (role = "user" par défaut,
     * cf. migration) et retourne directement un token Sanctum —
     * pas d'étape OTP pour l'inscription grand public.
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
        ]);

        return [
            'user' => $user,
            'token' => $user->createToken('mobile-app')->plainTextToken,
        ];
    }

    /**
     * Connexion utilisateur lambda. Si le compte est admin/rédacteur,
     * on bloque ici et on redirige explicitement vers le flux OTP —
     * ces rôles ne doivent jamais obtenir de token par simple mot de passe.
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ["Identifiants incorrects."],
            ]);
        }

        if (in_array($user->role, ['admin', 'redacteur'], true)) {
            throw ValidationException::withMessages([
                'email' => ["Ce compte doit se connecter via /admin/login (code de vérification)."],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('mobile-app')->plainTextToken,
        ];
    }

    /**
     * Étape 1 de la connexion admin/rédacteur : vérifie email + mot de
     * passe SANS émettre de token — le token n'arrive qu'après l'OTP
     * (voir OtpController::verifyLoginOtp).
     */
    public function attemptAdminCredentials(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ["Identifiants incorrects."],
            ]);
        }

        if (! in_array($user->role, ['admin', 'redacteur'], true)) {
            throw ValidationException::withMessages([
                'email' => ["Ce compte n'a pas accès au back-office."],
            ]);
        }

        return $user;
    }

    public function logout(User $user, string $currentTokenId): void
    {
        $user->tokens()->where('id', $currentTokenId)->delete();
    }

    /**
     * Déconnecte toutes les sessions actives — utilisé après un reset
     * de mot de passe, pour invalider tout token émis avec l'ancien mdp.
     */
    public function logoutEverywhere(User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Applique le nouveau mot de passe et révoque toutes les sessions
     * actives — appelé après vérification réussie de l'OTP de reset,
     * jamais directement depuis un controller.
     */
    public function resetPassword(User $user, string $newPassword): void
    {
        $user->forceFill(['password' => Hash::make($newPassword)])->save();
        $this->logoutEverywhere($user);
    }
}