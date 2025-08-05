<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commercants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('num_commerce')->unique();
            $table->string('password')->nullable();

            $table->string('type_piece');
            $table->string('numero_piece')->nullable();
            $table->string('autre_type_piece')->nullable();
            $table->string('photo_profil')->nullable();
            $table->string('photo_recto')->nullable();
            $table->string('photo_verso')->nullable();
            $table->json('autre_images')->nullable();

            // Relations
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->foreignId('mairie_id')->constrained()->onDelete('cascade');

            // Champs JSON
            $table->json('taxe_id')->nullable();
            $table->json('secteur_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commercants');
    }
};
