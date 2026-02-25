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
        Schema::table('mairies', function (Blueprint $table) {
            if (!Schema::hasColumn('mairies', 'photo_profil')) {
                $table->string('photo_profil')->nullable()->after('email');
            }
        });

        Schema::table('agents', function (Blueprint $table) {
            if (!Schema::hasColumn('agents', 'photo_profil')) {
                $table->string('photo_profil')->nullable()->after('email');
            }
        });

        Schema::table('finances', function (Blueprint $table) {
            if (!Schema::hasColumn('finances', 'photo_profil')) {
                $table->string('photo_profil')->nullable()->after('email');
            }
        });

        Schema::table('financiers', function (Blueprint $table) {
            if (!Schema::hasColumn('financiers', 'photo_profil')) {
                $table->string('photo_profil')->nullable()->after('email');
            }
        });
        
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'photo_profil')) {
                $table->string('photo_profil')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mairies', function (Blueprint $table) {
            $table->dropColumn('photo_profil');
        });
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('photo_profil');
        });
        Schema::table('finances', function (Blueprint $table) {
            $table->dropColumn('photo_profil');
        });
        Schema::table('financiers', function (Blueprint $table) {
            $table->dropColumn('photo_profil');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('photo_profil');
        });
    }
};
