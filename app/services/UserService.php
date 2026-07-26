<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * Change le rôle d'un utilisateur. On protège l'admin connecté
     * pour qu'il ne puisse pas se rétrograder lui-même par erreur et
     * se retrouver bloqué hors du back-office.
     */
    public function updateRole(User $target, string $newRole, User $actingAdmin): User
    {
        if ($target->id === $actingAdmin->id && $newRole !== 'admin') {
            throw ValidationException::withMessages([
                'role' => ["Tu ne peux pas retirer ton propre rôle admin."],
            ]);
        }

        $target->update(['role' => $newRole]);

        // Un changement de rôle invalide les sessions existantes :
        // si on vient de retirer les droits admin/rédacteur, on ne
        // veut pas qu'un token déjà émis continue de donner accès
        // au back-office.
        $target->tokens()->delete();

        return $target;
    }

    public function delete(User $target, User $actingAdmin): void
    {
        if ($target->id === $actingAdmin->id) {
            throw ValidationException::withMessages([
                'id' => ["Tu ne peux pas supprimer ton propre compte depuis cet écran."],
            ]);
        }

        $target->delete();
    }
}