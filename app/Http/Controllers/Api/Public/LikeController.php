<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\ToggleLikeRequest;
use App\Services\LikeService;

class LikeController extends Controller
{
    public function __construct(private LikeService $likeService) {}

    /** POST /likes — { likeable_type, likeable_id } */
    public function toggle(ToggleLikeRequest $request)
    {
        $result = $this->likeService->toggle($request->validated(), $request->user());

        return response()->json($result);
    }
}