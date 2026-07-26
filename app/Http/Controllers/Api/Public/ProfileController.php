<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService) {}

    /** GET /me/profile */
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    /** PUT /me/profile */
    public function update(UpdateProfileRequest $request)
    {
        $user = $this->profileService->update($request->user(), $request->validated());

        return new UserResource($user);
    }
}