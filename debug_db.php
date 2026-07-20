<?php
require 'vendor/autoload.php';
use Illuminate\Support\Facades\DB;
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
config(['database.connections.sqlite.database' => 'c:\Users\admin\OneDrive\Desktop\pwa\clinic-pwa\database\database.sqlite']);

try {
    echo "START TIMES:\n";
    print_r(DB::connection('sqlite')->table('start_times')->get()->pluck('name')->toArray());
    echo "\nCATEGORIES:\n";
    print_r(DB::connection('sqlite')->table('medicine_categories')->get()->pluck('name')->toArray());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
