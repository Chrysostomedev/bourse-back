<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class RegisterController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function store(RegisterRequest $request)
    {
        // Toute la logique (création + émission du token) vit dans le
        // service : le controller ne fait que valider (via le Request)
        // et formater la réponse (via la Resource).
        $result = $this->authService->register($request->validated());

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 201);
    }
}