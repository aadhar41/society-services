<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$categories = Illuminate\Support\Facades\DB::table('complaint_categories')->get();
echo "Total Categories: " . $categories->count() . PHP_EOL;
foreach ($categories as $cat) {
    echo "ID: " . $cat->id . " | Name: " . $cat->name . PHP_EOL;
}
