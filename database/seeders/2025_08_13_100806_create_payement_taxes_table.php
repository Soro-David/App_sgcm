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
        Schema::create('paiement_taxes', function (Blueprint $table) {
            $table->id();
            $table->json('secteur_id')->nullable()->change();

            $table->foreignId('agent_id')
                ->nullable()
                ->constrained('agents')
                ->onDelete('cascade');

            $table->foreignId('taxe_id')
                ->constrained('taxes')
                ->onDelete('cascade');

            $table->foreignId('mairie_ref')
                ->constrained('mairies')
                ->onDelete('cascade');

            $table->string('num_commerce')->nullable();
            $table->string('montant')->nullable();
            $table->string('statut')->default('payé');
            $table->string('periode',  50);
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
