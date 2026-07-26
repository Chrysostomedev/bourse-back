<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Scholarship;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService) {}

    /**
     * GET /comments?commentable_type=scholarship|post&commentable_id=123
     * Lecture publique (pas besoin d'être connecté pour lire les avis) —
     * seuls store/destroy exigent auth:sanctum (voir routes/public.php).
     */
    public function index(Request $request)
    {
        $request->validate([
            'commentable_type' => ['required', Rule::in(['scholarship', 'post'])],
            'commentable_id' => ['required', 'integer'],
        ]);

        $commentableClass = $request->string('commentable_type') === 'post' ? Post::class : Scholarship::class;

        $comments = Comment::query()
            ->where('commentable_type', $commentableClass)
            ->where('commentable_id', $request->integer('commentable_id'))
            ->whereNull('parent_id') // on ne remonte que les commentaires racine
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(20);

        return CommentResource::collection($comments);
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentService->store($request->validated(), $request->user());

        return new CommentResource($comment->load('user'));
    }

    public function destroy(Request $request, Comment $comment)
    {
        // Peut lever une ValidationException (422) si l'utilisateur
        // n'est ni l'auteur ni modérateur — voir CommentService::delete().
        $this->commentService->delete($comment, $request->user());

        return response()->json(['message' => 'Commentaire supprimé.']);
    }
}