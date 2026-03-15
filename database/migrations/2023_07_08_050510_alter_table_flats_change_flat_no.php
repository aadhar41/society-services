<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFlatsChangeFlatNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flats', function (Blueprint $table) {
            $table->string('flat_no')->nullable($value = true)->collation('utf8mb4_general_ci')->comment('Modified datatype from bigInteger to string.')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flats', function (Blueprint $table) {
            $table->bigInteger('flat_no')->nullable($value = true)->collation('utf8mb4_general_ci');
        });
    }
}
