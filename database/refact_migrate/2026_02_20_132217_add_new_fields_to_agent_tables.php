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
        $tables = ['agents', 'finances', 'financiers', 'mairies'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (! Schema::hasColumn($tableName, 'matricule')) {
                        // On vérifie si date_naissance existe pour le positionnement
                        if (Schema::hasColumn($tableName, 'date_naissance')) {
                            $table->string('matricule')->nullable()->after('date_naissance');
                        } else {
                            $table->string('matricule')->nullable();
                        }
                    }
                    if (! Schema::hasColumn($tableName, 'filiation')) {
                        if (Schema::hasColumn($tableName, 'email')) {
                            $table->string('filiation')->nullable()->after('email');
                        } else {
                            $table->string('filiation')->nullable();
                        }
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['agents', 'finances', 'financiers', 'mairies'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $columns = [];
                    if (Schema::hasColumn($tableName, 'matricule')) {
                        $columns[] = 'matricule';
                    }
                    if (Schema::hasColumn($tableName, 'filiation')) {
                        $columns[] = 'filiation';
                    }
                    if (! empty($columns)) {
                        $table->dropColumn($columns);
                    }
                });
            }
        }
    }
};
