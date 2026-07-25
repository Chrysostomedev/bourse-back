<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RequestPasswordResetRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\OtpService;

class PasswordResetController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private OtpService $otpService,
    ) {}

    /**
     * POST /password/request-reset
     * On répond le même message que l'email existe ou non, pour ne
     * pas laisser deviner quels emails sont enregistrés.
     */
    public function requestReset(RequestPasswordResetRequest $request)
    {
        $user = User::where('email', $request->validated('email'))->first();

        if ($user) {
            $this->otpService->generate($user, 'password_reset');
        }

        return response()->json([
            'message' => 'Si un compte existe avec cet email, un code a été envoyé.',
        ]);
    }

    /**
     * POST /password/reset
     * Vérifie le code puis applique le nouveau mot de passe. Toute la
     * logique (hash + révocation des sessions) vit dans AuthService.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->validated('email'))->firstOrFail();

        abort_unless(
            $this->otpService->verify($user, $request->validated('code'), 'password_reset'),
            422,
            'Code invalide ou expiré.'
        );

        $this->authService->resetPassword($user, $request->validated('password'));

        return response()->json([
            'message' => 'Mot de passe mis à jour. Reconnecte-toi avec le nouveau.',
        ]);
    }
}