<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function destroy(Request $request)
    {
        // currentAccessToken() est fourni par Sanctum sur le user
        // authentifié — on ne révoque QUE le token utilisé pour cette
        // requête, pas toutes les sessions de l'utilisateur.
        $this->authService->logout(
            $request->user(),
            $request->user()->currentAccessToken()->id,
        );

        return response()->json(['message' => 'Déconnecté.']);
    }
}