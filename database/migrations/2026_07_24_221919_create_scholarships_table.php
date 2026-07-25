<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();

            // --- Organisme intégré directement (pas de table organizations) ---
            $table->string('organism_name');
            $table->string('organism_logo')->nullable(); // chemin storage/app/public/scholarships/logos

            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('scholarship_type_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('funding_type', ['partielle', 'totale'])->default('totale');

            $table->text('objective');
            $table->text('conditions');
            $table->text('advantages');
            $table->json('additional_info')->nullable(); // liste de conseils/points

            $table->string('official_link')->nullable();

            // --- Image de couverture, prête pour le storage ---
            $table->string('cover_image')->nullable(); // storage/app/public/scholarships/{filename}

            $table->enum('status', ['brouillon', 'publie', 'archive'])->default('brouillon');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views_count')->default(0);

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};