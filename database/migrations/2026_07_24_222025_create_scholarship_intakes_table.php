<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_intakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained()->cascadeOnDelete();

            // ex: "Rentrée de septembre 2026", "Rentrée de janvier 2027"
            $table->string('intake_label');

            // Dates précises quand elles sont connues ; sinon un texte
            // libre suffit pour un affichage type "Mai/Juin 2026".
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('period_label_text')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_intakes');
    }
};