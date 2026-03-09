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

            $table->string('name', 150);

            // Informations personnelles
            $table->string('genre', 20)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('type_piece', 50)->nullable();
            $table->string('numero_piece', 100)->nullable();
            $table->string('adresse', 255)->nullable();
            $table->string('telephone1', 20)->nullable();
            $table->string('telephone2', 20)->nullable();

            // Références
            $table->string('mairie_ref', 100)->nullable();
            $table->string('added_by', 100)->nullable();

            $table->string('region', 100);
            $table->string('commune', 100)->nullable();
            $table->string('role', 50)->default('admin');

            // Auth
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();

            // Compléments
            $table->string('matricule', 100)->nullable();
            $table->string('filiation', 150)->nullable();
            $table->string('photo_profil', 255)->nullable();

            // OTP
            $table->string('otp_code', 10)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('status', 50)->default('pending');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financiers');
    }
};
