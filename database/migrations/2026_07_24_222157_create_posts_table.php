<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('content');

            // --- Média : image et/ou vidéo (ex: module "Web TV") ---
            $table->string('cover_image')->nullable(); // storage/app/public/posts/{filename}
            $table->string('video_url')->nullable();    // lien direct ou storage/app/public/posts/videos

            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();

            $table->enum('status', ['brouillon', 'publie', 'archive'])->default('brouillon');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};