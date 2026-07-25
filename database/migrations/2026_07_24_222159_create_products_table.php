<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['ebook', 'guide_pdf', 'modele_lettre', 'autre']);
            $table->text('description')->nullable();

            $table->unsignedInteger('price')->default(0); // en FCFA, 0 = gratuit
            $table->string('cover_image')->nullable();     // storage/app/public/products/covers
            $table->string('file_url')->nullable();         // fichier interne (storage) ou lien externe

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};