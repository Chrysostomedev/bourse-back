<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère le premier user (admin) ou le crée
        $author = User::first() ?? User::create([
            'name' => 'Admin BPT',
            'email' => 'admin@bpt.com',
            'password' => bcrypt('password123'),
        ]);

        // Crée 5 posts de test
        Post::create([
            'title' => 'Les meilleures bourses en 2026',
            'slug' => 'les-meilleures-bourses-en-2026',
            'excerpt' => 'Découvrez les bourses d\'études les plus prestigieuses cette année',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            'author_id' => $author->id,
            'status' => 'publie',
            'published_at' => now(),
            'cover_image' => null,
            'video_url' => null,
        ]);

        Post::create([
            'title' => 'Comment préparer votre candidature',
            'slug' => 'comment-preparer-votre-candidature',
            'excerpt' => 'Guide complet pour une candidature réussie',
            'content' => 'Faire une bonne candidature demande de la préparation. Voici les étapes essentielles: 1. Comprendre les critères, 2. Rédiger un bon CV, 3. Préparer votre lettre de motivation.',
            'author_id' => $author->id,
            'status' => 'publie',
            'published_at' => now()->subDays(2),
            'cover_image' => null,
            'video_url' => null,
        ]);

        Post::create([
            'title' => 'Témoignage: Ma bourse Canada',
            'slug' => 'temoignage-ma-bourse-canada',
            'excerpt' => 'L\'expérience d\'une étudiante qui a obtenu une bourse au Canada',
            'content' => 'J\'ai eu la chance d\'obtenir une bourse pour étudier au Canada. C\'était une expérience incroyable. Les universités canadiennes sont de haut niveau et l\'environnement d\'études est excellente.',
            'author_id' => $author->id,
            'status' => 'publie',
            'published_at' => now()->subDays(5),
            'cover_image' => null,
            'video_url' => null,
        ]);

        Post::create([
            'title' => 'Erreurs à éviter dans votre candidature',
            'slug' => 'erreurs-a-eviter-candidature',
            'excerpt' => 'Les 10 erreurs les plus courantes qui peuvent ruiner votre candidature',
            'content' => 'Beaucoup d\'étudiants font les mêmes erreurs. Voici ce qu\'il faut absolument éviter: oublier des documents, mal orthographier votre nom, envoyer un CV mal formaté, ignorer les instructions...',
            'author_id' => $author->id,
            'status' => 'publie',
            'published_at' => now()->subDays(7),
            'cover_image' => null,
            'video_url' => null,
        ]);

        Post::create([
            'title' => 'Bourses d\'excellence : quels profils recherchent-elles ?',
            'slug' => 'bourses-excellence-profils',
            'excerpt' => 'Analyse des critères de sélection des bourses d\'excellence',
            'content' => 'Les bourses d\'excellence recherchent des profils particuliers. Ils cherchent des étudiants avec d\'excellents résultats académiques, mais aussi des leaders potentiels et des personnes engagées socialement.',
            'author_id' => $author->id,
            'status' => 'publie',
            'published_at' => now()->subDays(10),
            'cover_image' => null,
            'video_url' => null,
        ]);
    }
}
