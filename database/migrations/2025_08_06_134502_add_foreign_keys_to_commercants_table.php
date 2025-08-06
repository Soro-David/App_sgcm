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
        Schema::table('commercants', function (Blueprint $table) {
            // Ajoute la colonne pour le secteur
            $table->foreignId('secteur_id')
                  ->nullable() // Ou à retirer si un secteur est toujours obligatoire
                  ->constrained('secteurs') // 'secteurs' est le nom de la table des secteurs
                  ->onDelete('set null'); // Ou 'cascade' si vous voulez supprimer le commerçant

            // Ajoute la colonne pour le type de contribuable
            $table->foreignId('type_contribuable_id')
                  ->nullable() // Ou à retirer si un type est toujours obligatoire
                  ->constrained('type_contribuables') // 'type_contribuables' est le nom de la table
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commercants', function (Blueprint $table) {
            $table->dropForeign(['secteur_id']);
            $table->dropColumn('secteur_id');
            
            $table->dropForeign(['type_contribuable_id']);
            $table->dropColumn('type_contribuable_id');
        });
    }
};