<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleOtpRequests
{
    /**
     * Limite les demandes de code OTP (login admin/rédacteur ET
     * demande de reset password) à 3 par tranche de 15 minutes,
     * par couple email + IP — évite qu'un tiers déclenche l'envoi
     * en boucle d'emails vers un utilisateur qu'il ne contrôle pas.
     *
     * Usage dans les routes : ->middleware('otp.throttle')
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'otp:' . $request->input('email') . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, maxAttempts: 3)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'message' => "Trop de tentatives. Réessaie dans {$seconds} secondes.",
            ], 429);
        }

        RateLimiter::hit($key, decaySeconds: 900); // 15 minutes

        return $next($request);
    }
}