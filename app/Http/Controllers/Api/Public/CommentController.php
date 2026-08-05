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

    /**
     * GET /posts/{postId}/comments
     * Récupère les commentaires d'un post spécifique (route mobile)
     */
    public function indexByPost(Request $request, int $postId)
    {
        // ✅ Vérifier que le post existe (retourne 404 si non)
        Post::findOrFail($postId);

        $comments = Comment::query()
            ->where('commentable_type', Post::class)
            ->where('commentable_id', $postId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(20);

        return CommentResource::collection($comments);
    }

    /**
     * POST /comments
     * Crée un commentaire (route générique)
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentService->store($request->validated(), $request->user());

        return new CommentResource($comment->load('user'));
    }

    /**
     * POST /posts/{postId}/comments
     * Crée un commentaire sur un post spécifique (route mobile)
     */
    public function storeByPost(Request $request, int $postId)
    {
        // Vérifier que le post existe
        $post = Post::findOrFail($postId);

        // Valider et créer le commentaire
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $this->commentService->store([
            'commentable_type' => Post::class,
            'commentable_id' => $postId,
            'content' => $validated['content'],
        ], $request->user());

        return new CommentResource($comment->load('user'));
    }

    /**
     * DELETE /comments/{comment}
     * Supprime un commentaire
     */
    public function destroy(Request $request, Comment $comment)
    {
        // Peut lever une ValidationException (422) si l'utilisateur
        // n'est ni l'auteur ni modérateur — voir CommentService::delete().
        $this->commentService->delete($comment, $request->user());

        return response()->json(['message' => 'Commentaire supprimé.']);
    }
}