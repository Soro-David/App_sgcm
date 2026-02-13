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
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            
            $table->string('motif')->nullable();  
            $table->text('description')->nullable();       
            $table->decimal('montant', 15, 2)->default(0); 
            
            $table->date('date_depense')->nullable(); 
            $table->string('mode_paiement')->nullable();
            $table->string('reference')->nullable();
            
            $table->unsignedBigInteger('agent_id')->nullable(); 
            // $table->string('mairie_ref')->nullable();
            
            $table->string('piece_jointe')->nullable();
            
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
            $table->string('mairie_ref')->references('mairie_ref')->on('mairies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
