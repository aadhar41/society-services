<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PostgreSQL handles enums as check constraints in Laravel
        // We need to drop the old constraint and add a new one
        DB::statement('ALTER TABLE units DROP CONSTRAINT IF EXISTS units_unit_type_check');
        DB::statement("ALTER TABLE units ADD CONSTRAINT units_unit_type_check CHECK (unit_type IN ('flat', 'shop', 'office', 'plot'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE units DROP CONSTRAINT IF EXISTS units_unit_type_check');
        DB::statement("ALTER TABLE units ADD CONSTRAINT units_unit_type_check CHECK (unit_type IN ('flat', 'shop', 'office'))");
    }
};
