<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Scholarship;
use App\Models\ScholarshipView;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatsService
{
    /**
     * Chiffres clés affichés en haut du dashboard admin.
     */
    public function overview(): array
    {
        return [
            'scholarships_published' => Scholarship::where('status', 'publie')->count(),
            'scholarships_draft' => Scholarship::where('status', 'brouillon')->count(),
            'posts_published' => Post::where('status', 'publie')->count(),
            'users_total' => User::where('role', 'user')->count(),
            'comments_total' => Comment::count(),
            'likes_total' => Like::count(),
        ];
    }

    /**
     * Nombre de vues de bourses regroupées par pays — sert le graphique
     * "Bourses les plus consultées par pays" du back-office.
     */
    public function viewsByCountry(): array
    {
        return ScholarshipView::query()
            ->join('countries', 'countries.id', '=', 'scholarship_views.country_id')
            ->selectRaw('countries.name as country, countries.flag_emoji, count(*) as views_count')
            ->groupBy('countries.id', 'countries.name', 'countries.flag_emoji')
            ->orderByDesc('views_count')
            ->get()
            ->toArray();
    }

    /**
     * Nombre de bourses publiées par filière — utile pour repérer les
     * domaines sous-représentés dans le catalogue.
     */
    public function scholarshipsByField(): array
    {
        return DB::table('scholarship_field_of_study')
            ->join('fields_of_study', 'fields_of_study.id', '=', 'scholarship_field_of_study.field_of_study_id')
            ->selectRaw('fields_of_study.name as field, count(*) as scholarships_count')
            ->groupBy('fields_of_study.id', 'fields_of_study.name')
            ->orderByDesc('scholarships_count')
            ->get()
            ->toArray();
    }
}