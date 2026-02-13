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
        Schema::table('financiers', function (Blueprint $table) {
            if (Schema::hasColumn('financiers', 'nom')) {
                $table->renameColumn('nom', 'name');
            }
            $table->string('genre')->nullable()->after('name');
            $table->date('date_naissance')->nullable()->after('genre');
            $table->string('type_piece')->nullable()->after('date_naissance');
            $table->string('numero_piece')->nullable()->after('type_piece');
            $table->string('adresse')->nullable()->after('numero_piece');
            $table->string('telephone1')->nullable()->after('adresse');
            $table->string('telephone2')->nullable()->after('telephone1');
            $table->string('mairie_ref')->nullable()->after('telephone2');
            $table->string('added_by')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financiers', function (Blueprint $table) {
            $table->dropColumn([
                'genre', 'date_naissance', 'type_piece', 'numero_piece', 
                'adresse', 'telephone1', 'telephone2', 'mairie_ref', 'added_by'
            ]);
            if (Schema::hasColumn('financiers', 'name')) {
                $table->renameColumn('name', 'nom');
            }
        });
    }
};
