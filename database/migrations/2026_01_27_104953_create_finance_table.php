<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->string('mairie_ref')->nullable();
            $table->string('name');
            $table->string('genre')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('type_piece')->nullable();
            $table->string('numero_piece')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone1')->nullable();
            $table->string('telephone2')->nullable();

            $table->string('region');
            $table->string('commune')->nullable();
            $table->string('role')->nullable();

            // Authentification
            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('last_activity')->nullable();

            // OTP et statut
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('status')->default('pending');

            // Ajout
            $table->string('added_by')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
