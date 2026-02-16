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
        // On désactive les clés étrangères pour éviter les erreurs lors du dropColumn
        Schema::disableForeignKeyConstraints();

        Schema::table('secteurs', function (Blueprint $table) {
            // Supprimer la contrainte if exists (nom standard Laravel)
            try {
                $table->dropForeign('secteurs_mairie_ref_foreign');
            } catch (\Exception $e) {
            }

            // Supprimer la colonne et la recréer
            $table->dropColumn('mairie_ref');
        });

        Schema::table('secteurs', function (Blueprint $table) {
            $table->string('mairie_ref')->nullable()->after('id');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secteurs', function (Blueprint $table) {
            $table->bigInteger('mairie_ref')->unsigned()->change();
            $table->foreign('mairie_ref')->references('id')->on('mairies')->onDelete('cascade');
        });
    }
};
