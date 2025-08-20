<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->timestamp('last_activity')->nullable()->after('remember_token');
        });

        Schema::table('mairies', function (Blueprint $table) {
            $table->timestamp('last_activity')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('last_activity');
        });

        Schema::table('mairies', function (Blueprint $table) {
            $table->dropColumn('last_activity');
        });
    }

};
