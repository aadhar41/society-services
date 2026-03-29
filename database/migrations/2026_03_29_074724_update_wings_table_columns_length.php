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
        Schema::table('wings', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('code', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wings', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('code', 10)->change();
        });
    }
};
