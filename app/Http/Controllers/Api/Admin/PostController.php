<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    public function index(Request $request)
    {
        $posts = Post::query()
            ->with('author')
            ->withCount(['likes', 'comments'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20);

        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request)
    {
        $post = $this->postService->create($request->validated(), $request->user()->id);

        return new PostResource($post->load('author'));
    }

    public function show(Post $post)
    {
        return new PostResource($post->load('author')->loadCount(['likes', 'comments']));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post = $this->postService->update($post, $request->validated());

        return new PostResource($post->load('author'));
    }

    public function destroy(Post $post)
    {
        $this->postService->delete($post);

        return response()->json(['message' => 'Publication supprimée.']);
    }

    public function publish(Post $post)
    {
        return new PostResource($this->postService->publish($post)->load('author'));
    }

    public function archive(Post $post)
    {
        return new PostResource($this->postService->archive($post)->load('author'));
    }
}