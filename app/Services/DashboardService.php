<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Partner;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DashboardService
{
    public function getHomeData(?User $user = null): array
    {
        try {
           $featured = Scholarship::with(['country', 'studyLevel', 'intakes'])
    ->orderByDesc('created_at')
    ->take(6)
    ->get();

            // Si tu as la colonne is_featured, décommente :
            // $featured = Scholarship::where('is_featured', true)->...->get();

            $recentPosts = Post::with(['author'])
                ->orderByDesc('created_at')
                ->take(2)
                ->get();

            $partners = Partner::orderByDesc('created_at')->take(10)->get();

            Log::info('DASHBOARD DATA', [
                'scholarships' => $featured->count(),
                'posts' => $recentPosts->count(),
                'partners' => $partners->count(),
            ]);

            return [
                'user' => $user,
                'stats' => ['activeBoursesCount' => $featured->count()],
                'partners' => $partners,
                'featuredScholarships' => $featured,
                'recentPosts' => $recentPosts,
            ];
        } catch (\Throwable $e) {
            Log::error('DASHBOARD ERROR: '.$e->getMessage());
            return [
                'user' => $user,
                'stats' => ['activeBoursesCount' => 0],
                'partners' => collect(),
                'featuredScholarships' => collect(),
                'recentPosts' => collect(),
            ];
        }
    }
}