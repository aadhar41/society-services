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
        // 1. Core Modules
        Schema::create('erp_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. System Roles (If not already managed)
        Schema::create('erp_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 3. Role-level Module Defaults
        Schema::create('erp_role_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('erp_roles')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('erp_modules')->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->unique(['role_id', 'module_id']);
        });

        // 4. Society-level Module Overrides
        Schema::create('erp_society_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->constrained('erp_societies')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('erp_modules')->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->unique(['society_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_society_modules');
        Schema::dropIfExists('erp_role_modules');
        Schema::dropIfExists('erp_roles');
        Schema::dropIfExists('erp_modules');
    }
};
