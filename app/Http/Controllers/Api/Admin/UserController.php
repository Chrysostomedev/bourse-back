<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    /**
     * GET /admin/users
     * Pas de "store" ici volontairement : la création de compte passe
     * toujours par /register (auth publique). Ce controller gère
     * uniquement la modération des comptes existants.
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->string('role')))
            ->latest()
            ->paginate(20);

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user)
    {
        $updated = $this->userService->updateRole(
            $user,
            $request->validated('role'),
            $request->user(), // l'admin actuellement connecté
        );

        return new UserResource($updated);
    }

    public function destroy(Request $request, User $user)
    {
        $this->userService->delete($user, $request->user());

        return response()->json(['message' => 'Utilisateur supprimé.']);
    }
}