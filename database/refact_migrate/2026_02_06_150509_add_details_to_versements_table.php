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
        Schema::table('versements', function (Blueprint $table) {
            $table->string('recorded_by')->nullable()->after('mairie_ref');
            $table->string('appreciation')->nullable()->after('reste');
            $table->string('nom_versement')->nullable()->after('agent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('versements', function (Blueprint $table) {
            $table->dropColumn(['recorded_by', 'appreciation', 'nom_versement']);
        });
    }
};
