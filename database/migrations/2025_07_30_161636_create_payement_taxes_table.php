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
        Schema::create('payement_taxes', function (Blueprint $table) {
            $table->id();
            $table->json('secteur_id')->nullable();
            $table->foreignId('taxe_id')->constrained()->onDelete('cascade');
            $table->foreignId('mairie_id')->constrained()->onDelete('cascade');
            $table->string('num_commerce')->nullable();
            $table->string('montant')->nullable();
            $table->string('statut')->default('payé');
            $table->string('periode', 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payement_taxes');
    }
};
