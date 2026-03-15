<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Societies Table
        Schema::table('societies', function (Blueprint $table) {
            $table->string('registration_no')->nullable()->after('unique_code');
            $table->string('contact_email')->nullable()->after('contact');
            $table->string('office_contact')->nullable()->after('contact');
            $table->text('map_link')->nullable()->after('address');
        });

        // Plots Table
        Schema::table('plots', function (Blueprint $table) {
            $table->string('plot_area')->nullable()->after('total_flats');
        });

        // Maintenances Table
        Schema::table('maintenances', function (Blueprint $table) {
            $table->enum('payment_mode', ['1', '2', '3', '4', '5'])->nullable()->default('1')->comment('1:Cash, 2:UPI/Paytm, 3:Cheque, 4:Bank Transfer, 5:Other')->after('amount');
            $table->string('transaction_id')->nullable()->after('payment_mode');
        });

        // Expenses Table
        Schema::table('expenses', function (Blueprint $table) {
            $table->enum('payment_mode', ['1', '2', '3', '4', '5'])->nullable()->default('1')->comment('1:Cash, 2:UPI/Paytm, 3:Cheque, 4:Bank Transfer, 5:Other')->after('amount');
            $table->string('transaction_id')->nullable()->after('payment_mode');
            $table->string('category')->nullable()->after('type')->comment('Payee or Category name like Ravi, Guard, etc.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('societies', function (Blueprint $table) {
            $table->dropColumn(['registration_no', 'contact_email', 'office_contact', 'map_link']);
        });

        Schema::table('plots', function (Blueprint $table) {
            $table->dropColumn(['plot_area']);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropColumn(['payment_mode', 'transaction_id']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['payment_mode', 'transaction_id', 'category']);
        });
    }
}
