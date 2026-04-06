<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::all(['id', 'email', 'is_superadmin']);
foreach ($users as $user) {
    echo "ID: {$user->id}, Email: {$user->email}, SuperAdmin: " . ($user->is_superadmin ? 'YES' : 'NO') . "\n";
}
