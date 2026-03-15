<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('data/sql/cities.sql');
        
        if (File::exists($path)) {
            $sql = File::get($path);
            
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('cities')->truncate();
            
            // For cities.sql, it's quite large. We'll extract INSERT statements.
            // Using a limit or splitting might be better, but preg_match_all should handle 300KB.
            preg_match_all("/INSERT INTO `cities` .*?;/s", $sql, $matches);
            
            foreach ($matches[0] as $query) {
                DB::unprepared($query);
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->command->info('Cities table seeded!');
        } else {
            $this->command->error('cities.sql file not found at ' . $path);
        }
    }
}
