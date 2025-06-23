<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$company = DB::table('company_info')->first();
echo "Logo field: " . $company->logo . PHP_EOL;
echo "Expected path: storage/logos/" . $company->logo . PHP_EOL;
echo "Full public path: " . public_path('storage/logos/' . $company->logo) . PHP_EOL;
echo "File exists: " . (file_exists(public_path('storage/logos/' . $company->logo)) ? 'YES' : 'NO') . PHP_EOL;
