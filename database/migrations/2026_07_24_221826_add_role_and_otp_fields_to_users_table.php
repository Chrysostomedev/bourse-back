<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rôle applicatif : admin (accès total), redacteur (contenu),
            // user (utilisateur front, valeur par défaut à l'inscription)
            $table->enum('role', ['admin', 'redacteur', 'user'])
                ->default('user')
                ->after('email');

            $table->string('avatar')->nullable()->after('role');

            // --- Colonnes OTP, directement sur users plutôt qu'une table
            // dédiée : un seul code actif à la fois par utilisateur suffit
            // pour nos deux usages (login admin/rédacteur, reset password).
            $table->string('otp_code', 6)->nullable()->after('avatar');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->enum('otp_type', ['login', 'password_reset', 'email_verification'])
                ->nullable()
                ->after('otp_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar', 'otp_code', 'otp_expires_at', 'otp_type']);
        });
    }
};