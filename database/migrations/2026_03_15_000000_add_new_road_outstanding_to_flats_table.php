<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewRoadOutstandingToFlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('flats', function (Blueprint $table) {
            $table->float('new_road_outstanding', 10, 2)->default(0)->after('tenant_contact');
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
            $table->dropColumn('new_road_outstanding');
        });
    }
}
