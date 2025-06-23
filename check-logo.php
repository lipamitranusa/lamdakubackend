<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$company = DB::table('company_info')->first();
echo 'Logo file: ' . ($company->logo ?? 'none') . PHP_EOL;
echo 'Company name: ' . ($company->company_name ?? 'none') . PHP_EOL;
