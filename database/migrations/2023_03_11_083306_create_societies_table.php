<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocietiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('societies', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code')->nullable($value = true);
            $table->unsignedBigInteger('user_id')->comment('Created By User');
            $table->string('name')->nullable($value = true);
            $table->string('slug')->nullable($value = true);
            $table->text('address')->nullable($value = true);
            $table->string('contact')->nullable($value = true);
            $table->text('description')->nullable($value = true);
            $table->bigInteger('country')->nullable($value = true);
            $table->bigInteger('state')->nullable($value = true);
            $table->bigInteger('city')->nullable($value = true);
            $table->string('postcode')->nullable($value = true);
            $table->enum('status', ['1', '0'])->default("1")->comment('[1 => "Enabled", 0 => "Disabled"]');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('societies');
    }
}
