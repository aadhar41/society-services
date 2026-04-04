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
        Schema::table('notices', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('body')->constrained('complaint_categories')->nullOnDelete();
        });

        Schema::table('erp_documents', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('title')->constrained('complaint_categories')->nullOnDelete();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('email')->constrained('complaint_categories')->nullOnDelete();
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('name')->constrained('complaint_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('erp_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
