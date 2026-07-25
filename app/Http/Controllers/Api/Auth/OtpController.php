<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use App\Services\OtpService;

class OtpController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private OtpService $otpService,
    ) {}

    /**
     * Étape 1 — POST /admin/login
     * Vérifie email + mot de passe (sans émettre de token), puis
     * envoie un code à 6 chiffres par email. Le compte doit être
     * admin ou rédacteur (sinon 422, cf. AuthService).
     */
    public function requestLoginOtp(LoginRequest $request)
    {
        $user = $this->authService->attemptAdminCredentials(
            $request->validated('email'),
            $request->validated('password'),
        );

        $this->otpService->generate($user, 'login');

        return response()->json([
            'message' => 'Un code de vérification a été envoyé par email.',
        ]);
    }

    /**
     * Étape 2 — POST /admin/login/verify
     * Vérifie le code reçu par email. Si valide, émet le token Sanctum
     * qui donnera accès aux routes du back-office.
     */
    public function verifyLoginOtp(VerifyOtpRequest $request)
    {
        $user = User::where('email', $request->validated('email'))->firstOrFail();

        abort_unless(
            $this->otpService->verify($user, $request->validated('code'), 'login'),
            422,
            'Code invalide ou expiré.'
        );

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('admin-session')->plainTextToken,
        ]);
    }
}