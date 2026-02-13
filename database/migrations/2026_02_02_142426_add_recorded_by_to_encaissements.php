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
        Schema::table('encaissements', function (Blueprint $table) {
            $table->unsignedBigInteger('recorded_by')->nullable()->after('agent_id');
            $table->foreign('recorded_by')->references('id')->on('mairies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encaissements', function (Blueprint $table) {
            $table->dropForeign(['recorded_by']);
            $table->dropColumn('recorded_by');
        });
    }
};
