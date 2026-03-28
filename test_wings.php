<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domain\Society\Models\Wing;

try {
    $wings = Wing::withCount('units')->get();
    echo "Success: " . $wings->count() . " wings found\n";
    foreach ($wings as $wing) {
        echo "- Wing: " . $wing->name . " (Units: " . $wing->units_count . ")\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack Trace: " . $e->getTraceAsString() . "\n";
}
