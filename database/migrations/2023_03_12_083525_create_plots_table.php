<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code')->nullable($value = true);
            $table->unsignedBigInteger('user_id')->comment('Created By User');
            $table->unsignedBigInteger('society_id')->comment('Associated Society for the plot.');
            $table->unsignedBigInteger('block_id')->comment('Associated Block for the plot.');
            $table->string('name')->nullable($value = true);
            $table->string('slug')->nullable($value = true);
            $table->bigInteger('total_floors')->nullable($value = true);
            $table->bigInteger('total_flats')->nullable($value = true);
            $table->text('description')->nullable($value = true);
            $table->enum('status', ['1', '0'])->default("1")->comment('[1 => "Enabled", 0 => "Disabled"]');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('society_id')->references('id')->on('societies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plots');
    }
}
