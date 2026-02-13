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
        Schema::create('historique_recharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercant_id')->constrained('commercants');
            $table->decimal('montant', 10, 2);
            $table->string('reference', 191)->unique();
            $table->string('mode_paiement')->nullable(); // CinetPay, etc.
            $table->string('statut')->default('réussi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_recharges');
    }
};
