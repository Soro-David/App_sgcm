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
        Schema::create('mairies', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('genre');
            $table->date('date_naissance');
            $table->string('type_piece');
            $table->string('numero_piece');
            $table->string('adresse');
            $table->string('telephone1');
            $table->string('telephone2')->nullable();

            $table->string('region');
            $table->string('commune')->nullable();
            $table->string('role')->default('admin');

            // Authentification
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            // OTP et statut
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('status')->default('pending');

            $table->rememberToken();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mairies');
    }
};
