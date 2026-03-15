<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path('data/sql/countries.sql');
        
        if (File::exists($path)) {
            $sql = File::get($path);
            
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Truncate table before seeding to avoid duplicates
            DB::table('countries')->truncate();
            
            // Execute the SQL. We only want the INSERT statements to be safe, 
            // but the file is a full dump. We'll extract INSERT statements.
            preg_match_all("/INSERT INTO `countries` .*?;/s", $sql, $matches);
            
            foreach ($matches[0] as $query) {
                DB::unprepared($query);
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->command->info('Countries table seeded!');
        } else {
            $this->command->error('countries.sql file not found at ' . $path);
        }
    }
}
