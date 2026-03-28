<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RegionalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for truncation (PostgreSQL specific)
        DB::statement('SET CONSTRAINTS ALL DEFERRED');
        
        // Truncate in order to avoid FK violations
        DB::table('cities')->delete();
        DB::table('states')->delete();
        DB::table('countries')->delete();

        $sqlFiles = [
            'countries.sql' => 'countries',
            'states.sql' => 'states',
            'cities.sql' => 'cities',
        ];

        foreach ($sqlFiles as $filename => $table) {
            $path = base_path("data/sql/{$filename}");
            if (!File::exists($path)) {
                $this->command->warn("File not found: {$path}");
                continue;
            }

            $this->command->info("Seeding {$table} from {$filename}...");
            
            $sql = File::get($path);
            
            // Clean MySQL specific syntax for PostgreSQL
            $sql = $this->cleanSqlForPostgres($sql);
            
            // Execute the DNA of the SQL
            try {
                DB::unprepared($sql);
                
                // Update sequences for PostgreSQL after manual ID insertion
                $this->updateSequence($table);
                
            } catch (\Exception $e) {
                $this->command->error("Error seeding {$table}: " . $e->getMessage());
            }
        }
    }

    /**
     * Clean MySQL-style SQL for PostgreSQL
     */
    private function cleanSqlForPostgres(string $sql): string
    {
        // Remove MySQL comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Remove MySQL specific SET commands
        $sql = preg_replace('/SET SQL_MODE = .*?;/i', '', $sql);
        $sql = preg_replace('/SET time_zone = .*?;/i', '', $sql);
        $sql = preg_replace('/SET NAMES .*?;/i', '', $sql);
        
        // Remove START TRANSACTION and COMMIT from within the file (we handle it or want it clear)
        $sql = preg_replace('/START TRANSACTION;/i', '', $sql);
        $sql = preg_replace('/COMMIT;/i', '', $sql);
        
        // Remove backticks
        $sql = str_replace('`', '', $sql);
        
        // Remove MySQL Engine/Charset options from CREATE TABLE
        $sql = preg_replace('/ENGINE=InnoDB.*?;/i', ';', $sql);
        
        // Remove DROP TABLE and CREATE TABLE if they exist (we handle truncation)
        $sql = preg_replace('/DROP TABLE IF EXISTS .*?;/i', '', $sql);
        $sql = preg_replace('/CREATE TABLE IF NOT EXISTS .*?\(.*?\).*?;/s', '', $sql);

        // Remove any remaining trailing semicolons or empty lines resulting from replacements
        $sql = preg_replace('/^[\s;]+|[\s;]+$/m', '', $sql);

        return trim($sql);
    }

    /**
     * Update PostgreSQL sequences after manual ID insertion
     */
    private function updateSequence(string $table): void
    {
        $idColumn = 'id';
        $seqName = "{$table}_{$idColumn}_seq";
        
        // Check if sequence exists and update it
        DB::statement("SELECT setval('{$seqName}', (SELECT MAX({$idColumn}) FROM {$table}))");
    }
}
