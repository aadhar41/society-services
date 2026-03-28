<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = [
    'wings', 'units', 'parking_slots', 'members', 
    'complaint_categories', 'complaints', 
    'accounts', 'account_groups', 'financial_years',
    'facilities', 'facility_slots', 'bookings'
];

foreach ($tables as $table) {
    try {
        $count = Illuminate\Support\Facades\DB::table($table)->count();
        echo "Table: $table | Rows: $count" . PHP_EOL;
    } catch (\Exception $e) {
        echo "Table: $table | Error: " . $e->getMessage() . PHP_EOL;
    }
}
