<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fields_of_study', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: "Informatique", "Droit", "Toutes filières"
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fields_of_study');
    }
};