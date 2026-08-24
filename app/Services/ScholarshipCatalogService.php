<?php

namespace App\Services;

use App\Models\Scholarship;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ScholarshipCatalogService
{
    /**
     * Recherche + filtres combinables.
     */
   public function search(array $filters = []): Collection
{
    $query = Scholarship::query()
        ->with(['country', 'studyLevel', 'intakes']);
        // ->where('status', 'published')   // ← commente pour tester
        // ->whereNull('deleted_at');       // ← commente pour tester

    // ... le reste des filtres (q, country_id, etc.) reste identique

    return $query   
        ->orderByDesc('is_featured')
        ->orderByDesc('created_at')
        ->get();
}

    /**
     * Bourses mises en avant (pour le dashboard / section featured).
     */
    public function featured(int $limit = 6): Collection
    {
        return Scholarship::query()
            ->with(['country', 'studyLevel', 'intakes'])
            ->where('status', 'published')
            ->where('is_featured', true)
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->take($limit)
            ->get();
    }

    /**
     * Détail d’une bourse par slug + incrémentation des vues.
     */
 public function findBySlugAndRecordView(string $slug): Scholarship
{
    $scholarship = Scholarship::query()
        ->with(['country', 'studyLevel', 'intakes', 'scholarshipType'])
        ->where(function ($q) use ($slug) {
            // Accepte aussi bien le slug que l'id numérique
            $q->where('slug', $slug);

            if (is_numeric($slug)) {
                $q->orWhere('id', $slug);
            }
        })
        ->where(function ($q) {
            // Accepte 'published', NULL, et empty string ''
            $q->where('status', 'published')
              ->orWhereNull('status')
              ->orWhere('status', '');
        })
        ->whereNull('deleted_at')
        ->firstOrFail();

    $scholarship->increment('views_count');

    return $scholarship;
}
}