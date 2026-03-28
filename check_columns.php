<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$table = $argv[1] ?? 'units';
$columns = Illuminate\Support\Facades\DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = ?", [$table]);
foreach ($columns as $column) {
    echo $column->column_name . ': ' . $column->data_type . PHP_EOL;
}
