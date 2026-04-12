<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Per-society, per-role module access control
        // Allows Society Admin to define which modules Council Members and Staff can access
        Schema::create('erp_society_role_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->constrained('erp_societies')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('erp_roles')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('erp_modules')->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['society_id', 'role_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_society_role_modules');
    }
};
