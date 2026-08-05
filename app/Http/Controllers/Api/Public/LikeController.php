<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\ToggleLikeRequest;
use App\Models\Post;
use App\Services\LikeService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(private LikeService $likeService) {}

    /**
     * POST /likes — { likeable_type, likeable_id }
     * Route générique pour liker n'importe quel type (scholarship, post, etc)
     */
    public function toggle(ToggleLikeRequest $request)
    {
        $result = $this->likeService->toggle($request->validated(), $request->user());

        return response()->json($result);
    }

    /**
     * POST /posts/{postId}/like
     * Route spécifique mobile pour liker un post
     */
    public function togglePost(Request $request, int $postId)
    {
        // Vérifier que le post existe
        $post = Post::findOrFail($postId);

        // Utiliser le service générique avec les bons paramètres
        $result = $this->likeService->toggle([
            'likeable_type' => Post::class,
            'likeable_id' => $postId,
        ], $request->user());

        return response()->json($result);
    }
}