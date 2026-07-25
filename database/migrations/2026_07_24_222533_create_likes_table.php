<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Polymorphe : un like peut porter sur une Bourse ou un Post
            $table->morphs('likeable'); // crée likeable_id + likeable_type

            $table->timestamps();

            // Un utilisateur ne peut liker qu'une seule fois le même élément
            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};