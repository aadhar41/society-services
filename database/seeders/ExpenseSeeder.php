<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement("SET session_replication_role = 'replica';");
        \App\Models\Expense::truncate();
        \Illuminate\Support\Facades\DB::statement("SET session_replication_role = 'origin';");

        \App\Models\Expense::factory(10)->create();
    }
}
