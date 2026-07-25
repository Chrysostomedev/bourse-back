<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->cascadeOnDelete();

            // nullable : un visiteur non connecté peut aussi consulter une bourse
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Dénormalisé depuis scholarship.country_id au moment de la vue,
            // pour que les stats restent correctes même si le pays de la
            // bourse change plus tard.
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamp('viewed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_views');
    }
};