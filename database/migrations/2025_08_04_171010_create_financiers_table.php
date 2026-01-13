<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('region');
            $table->string('commune')->nullable();
            $table->string('role')->default('admin');

            // Limiter la taille à 191 pour éviter l'erreur d'index
            $table->string('email', 191)->unique();

            $table->timestamp('email_verified_at')->nullable();
            
            // Renommé mot_de_passe en password pour respecter convention
            $table->string('password')->nullable();

            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('status')->default('pending');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financiers');
    }
};
