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
        Schema::table('agents', function (Blueprint $table) {
            $table->string('type_piece')->nullable();
            $table->string('numero_piece')->nullable();
            $table->string('genre')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone1')->nullable();
            $table->string('telephone2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            Schema::dropIfExists('agents');
        });
    }
};
