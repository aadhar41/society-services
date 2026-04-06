<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domain\Accounting\Models\Account;
use App\Domain\Accounting\Models\AccountGroup;

echo "GROUPS:\n";
foreach (AccountGroup::all() as $group) {
    echo "ID: {$group->id}, Name: {$group->name}, Code: {$group->code}\n";
}

echo "\nACCOUNTS (Society 6):\n";
foreach (Account::where('society_id', 6)->get() as $acc) {
    echo "ID: {$acc->id}, Name: {$acc->name}, Code: {$acc->code}\n";
}
