<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Usage dans les routes : ->middleware('role:admin,redacteur')
     * Les rôles autorisés sont passés en paramètres du middleware,
     * séparés par une virgule dans la définition de la route.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_if(! $user, 401, 'Non authentifié.');
        abort_unless(in_array($user->role, $roles, true), 403, "Accès réservé à : " . implode(', ', $roles));

        return $next($request);
    }
}