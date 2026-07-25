<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // ex: "Canada"
            $table->string('code_iso2', 2)->unique(); // ex: "CA"
            $table->string('flag_emoji', 8)->nullable(); // ex: "🇨🇦"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};