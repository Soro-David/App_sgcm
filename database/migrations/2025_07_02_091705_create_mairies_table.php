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
        // Schema::create('mairies', function (Blueprint $table) {
        //     $table->id();

        //     // Ajout
        //     $table->string('mairie_ref')->nullable();

        //     $table->string('name');
        //     $table->string('genre')->nullable();
        //     $table->date('date_naissance')->nullable();
        //     // $table->string('type_agent')->nullable();
        //     $table->string('type_piece')->nullable();
        //     $table->string('numero_piece')->nullable();
        //     $table->string('adresse', 255)->nullable();
        //     $table->string('telephone1')->nullable();
        //     $table->string('telephone2')->nullable();

        //     $table->string('region');
        //     $table->string('commune')->nullable();
        //     $table->string('role')->default('admin');

        //     // Ajout
        //     $table->string('matricule')->nullable();
        //     $table->string('filiation')->nullable();
        //     $table->string('photo_profil')->nullable();
        //     // Authentification
        //     $table->string('email', 191)->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password')->nullable();
        //     $table->timestamp('last_activity')->nullable();

        //     // OTP et statut
        //     $table->string('otp_code')->nullable();
        //     $table->timestamp('otp_expires_at')->nullable();
        //     $table->string('status')->default('pending');

        //     // Ajout
        //     $table->string('added_by')->nullable();

        //     $table->rememberToken();
        //     $table->timestamps();
        // });

        Schema::create('mairies', function (Blueprint $table) {
            $table->id();

            $table->string('mairie_ref', 100)->nullable();

            $table->string('name', 150);
            $table->string('genre', 20)->nullable();
            $table->date('date_naissance')->nullable();

            $table->string('type_piece', 50)->nullable();
            $table->string('numero_piece', 100)->nullable();
            $table->string('adresse', 255)->nullable();

            $table->string('telephone1', 20)->nullable();
            $table->string('telephone2', 20)->nullable();

            $table->string('region', 100);
            $table->string('commune', 100)->nullable();
            $table->string('role', 50)->default('admin');

            $table->string('matricule', 100)->nullable();
            $table->string('filiation', 150)->nullable();
            $table->string('photo_profil', 255)->nullable();

            $table->string('email', 191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();
            $table->timestamp('last_activity')->nullable();

            $table->string('otp_code', 10)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('status', 50)->default('pending');

            $table->string('added_by', 100)->nullable();

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
