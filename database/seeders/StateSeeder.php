<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('data/sql/states.sql');
        
        if (File::exists($path)) {
            $sql = File::get($path);
            
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('states')->truncate();
            
            preg_match_all("/INSERT INTO `states` .*?;/s", $sql, $matches);
            
            foreach ($matches[0] as $query) {
                DB::unprepared($query);
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->command->info('States table seeded!');
        } else {
            $this->command->error('states.sql file not found at ' . $path);
        }
    }
}
