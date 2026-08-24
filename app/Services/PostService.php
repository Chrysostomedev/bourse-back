<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    public function create(array $data, int $authorId): Post
    {
        return Post::create([
            'title' => $data['title'],
            'slug' => $this->uniqueSlug($data['title']),
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'cover_image' => $this->storeImage($data['cover_image'] ?? null),
            'video_url' => $data['video_url'] ?? null,
            'author_id' => $authorId,
            'status' => $data['status'],
            // Un post passé directement en "publié" à la création prend
            // sa date de publication maintenant.
            'published_at' => $data['status'] === 'publie' ? now() : null,
        ]);
    }

    public function update(Post $post, array $data): Post
    {
        $post->update([
            'title' => $data['title'],
            'slug' => $post->title === $data['title'] ? $post->slug : $this->uniqueSlug($data['title'], $post->id),
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'cover_image' => isset($data['cover_image'])
                ? $this->storeImage($data['cover_image'], $post->cover_image)
                : $post->cover_image,
            'video_url' => $data['video_url'] ?? null,
            'status' => $data['status'],
            // Ne fixe published_at que lors du PREMIER passage en publié
            // (on ne veut pas remettre la date à "maintenant" à chaque
            // simple modification de contenu).
            'published_at' => $data['status'] === 'publie' && ! $post->published_at
                ? now()
                : $post->published_at,
        ]);

        return $post;
    }

    public function publish(Post $post): Post
    {
        $post->update([
            'status' => 'publie',
            'published_at' => $post->published_at ?? now(),
        ]);

        return $post;
    }

    public function archive(Post $post): Post
    {
        $post->update(['status' => 'archive']);

        return $post;
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            Post::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function storeImage(?UploadedFile $file, ?string $previousPath = null): ?string
    {
        if (! $file) {
            return $previousPath;
        }

        if ($previousPath) {
            Storage::disk('public')->delete($previousPath);
        }

        return $file->store('posts', 'public');
    }
}