<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('kind', ['coaching', 'formation', 'dossier']);
            $table->text('description');

            $table->unsignedInteger('price')->default(0); // en FCFA, 0 = gratuit
            $table->string('image')->nullable(); // storage/app/public/services/{filename}

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};