<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    /** GET /posts */
    public function index()
    {
        $posts = Post::query()
            ->where('status', 'publie')
            ->with('author')
            ->withCount(['likes', 'comments'])
            ->latest('published_at')
            ->paginate(15);

        return PostResource::collection($posts);
    }

    /** GET /posts/{slug} */
    public function show(string $slug)
    {
        $post = Post::query()
            ->where('status', 'publie')
            ->where('slug', $slug)
            ->with('author')
            ->withCount(['likes', 'comments'])
            ->firstOrFail();

        $post->increment('views_count');

        return new PostResource($post);
    }
}