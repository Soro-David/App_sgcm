<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payement_taxes', function (Blueprint $table) {
            // Ajoute la colonne si elle n'existe pas
            if (!Schema::hasColumn('payement_taxes', 'periode')) {
                $table->string('periode', 7)->after('statut'); // ou 'after' un autre champ existant
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payement_taxes', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
    }
};
