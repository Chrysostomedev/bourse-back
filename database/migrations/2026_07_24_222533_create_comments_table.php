<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Polymorphe : un commentaire peut porter sur une Bourse ou un Post
            $table->morphs('commentable'); // crée commentable_id + commentable_type

            $table->text('content');

            // Réponse à un autre commentaire (fil de discussion à 1 niveau)
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};