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
            ->withCount([
                'likes' => function ($query) {
                    $query->where('likeable_type', 'post');
                },
                'comments' => function ($query) {
                    $query->where('commentable_type', 'post')->whereNull('parent_id');
                }
            ])
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
            ->withCount([
                'likes' => function ($query) {
                    $query->where('likeable_type', 'post');
                },
                'comments' => function ($query) {
                    $query->where('commentable_type', 'post')->whereNull('parent_id');
                }
            ])
            ->firstOrFail();

        $post->increment('views_count');

        return new PostResource($post);
    }
}