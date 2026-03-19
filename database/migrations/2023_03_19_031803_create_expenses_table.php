<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code')->nullable($value = true);
            $table->unsignedBigInteger('user_id')->comment('Created By User');
            $table->unsignedBigInteger('society_id')->comment('Associated Society for the maintenances.');
            $table->unsignedBigInteger('block_id')->comment('Associated Block for the maintenances.');
            $table->unsignedBigInteger('plot_id')->comment('Associated Plot for the maintenances.');
            $table->unsignedBigInteger('flat_id')->comment('Associated Flat for the maintenances.');
            $table->enum('type', ['1', '2', '3', '4', '5'])->default("1")->comment('getMaintenanceTypes[1 => "Monthly", 2 => "Lift", 3 => "donation", 4 => "contribution", 5 => "other"]');
            $table->string('date')->nullable($value = true)->comment('Submission Date');
            $table->string('year')->nullable($value = true);
            $table->string('month')->nullable($value = true);
            $table->float('amount', 8, 2);
            $table->text('description')->nullable($value = true);
            $table->string('attachments')->nullable($value = true);
            $table->enum('payment_status', ['1', '2', '3'])->default("1")->comment('[1 => "Complete", 2 => "Pending", 3 => "Extra"]');
            $table->enum('status', ['1', '0'])->default("1")->comment('[1 => "Enabled", 0 => "Disabled"]');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('society_id')->references('id')->on('societies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('block_id')->references('id')->on('blocks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('plot_id')->references('id')->on('plots')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
