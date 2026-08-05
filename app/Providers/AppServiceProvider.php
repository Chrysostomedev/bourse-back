<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Désactive l'enveloppe "data" des API Resources
        JsonResource::withoutWrapping();

        // Définit les alias des relations polymorphiques
        Relation::enforceMorphMap([
            'scholarship' => Scholarship::class,
            'post'        => Post::class,
            'user'        => User::class,
        ]);
    }
}