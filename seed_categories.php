<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$societies = Illuminate\Support\Facades\DB::table('erp_societies')->pluck('id');

$categories = [
    ['name' => 'Plumbing', 'description' => 'Leakage, taps, pipes, etc.', 'sla_hours' => 24],
    ['name' => 'Electrical', 'description' => 'Lights, wiring, meters, etc.', 'sla_hours' => 24],
    ['name' => 'Security', 'description' => 'Guards, CCTV, gate issues, etc.', 'sla_hours' => 12],
    ['name' => 'Cleaning', 'description' => 'Housekeeping, garbage, etc.', 'sla_hours' => 48],
    ['name' => 'Lifts', 'description' => 'Elevator malfunctions', 'sla_hours' => 12],
    ['name' => 'General', 'description' => 'Other issues', 'sla_hours' => 72],
];

foreach ($societies as $societyId) {
    foreach ($categories as $cat) {
        Illuminate\Support\Facades\DB::table('complaint_categories')->updateOrInsert(
            ['name' => $cat['name'], 'society_id' => $societyId],
            [
                'uuid' => Illuminate\Support\Str::uuid(),
                'description' => $cat['description'],
                'sla_hours' => $cat['sla_hours'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}

echo "Seeded " . count($categories) . " categories for " . $societies->count() . " societies." . PHP_EOL;
