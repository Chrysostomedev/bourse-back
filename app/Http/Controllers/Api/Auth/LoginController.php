<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class LoginController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function store(LoginRequest $request)
    {
        // AuthService::login() lève une ValidationException (donc une
        // réponse 422 automatique) si les identifiants sont mauvais,
        // ou si le compte est admin/rédacteur (ils doivent passer par
        // /admin/login). Rien à gérer ici.
        $result = $this->authService->login(
            $request->validated('email'),
            $request->validated('password'),
        );

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ]);
    }
}