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
        Schema::create('type_contribuables', function (Blueprint $table) {
            $table->id();

            // Limite la taille pour éviter l'erreur d'index
            $table->string('libelle', 191)->unique();

            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->string('mairie_ref')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_contribuables');
    }
};
