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
        Schema::table('erp_documents', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->string('category')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('erp_documents', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });

        Schema::table('notices', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
        });
    }
};
