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
            if (! Schema::hasColumn('commercants', 'mairie_ref')) {
                $table->string('mairie_ref')->nullable()->after('mairie_id');
            }
            if (! Schema::hasColumn('commercants', 'last_activity')) {
                $table->timestamp('last_activity')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commercants', function (Blueprint $table) {
            $table->dropColumn(['mairie_ref', 'last_activity']);
        });
    }
};
