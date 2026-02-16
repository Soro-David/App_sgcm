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
        Schema::create('versements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxe_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade')->nullable();
            $table->string('mairie_ref')->nullable();
            // Ajout
            $table->string('recorded_by')->nullable();
            $table->string('appreciation')->nullable();
            $table->string('nom_versement')->nullable();

            $table->string('montant_percu')->nullable();
            $table->string('montant_verse')->nullable();
            $table->string('reste')->nullable();
            // Ajout
            $table->string('total_due')->nullable();
            $table->string('previous_debt')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versements');
    }
};
