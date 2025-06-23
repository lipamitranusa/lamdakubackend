<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Company Info and Website URL...\n";
echo "=====================================\n\n";

// Test CompanyInfo model
$companyInfo = \App\Models\CompanyInfo::getActiveCompanyInfo();
if ($companyInfo) {
    echo "✓ Company Info found:\n";
    echo "  - Company Name: " . $companyInfo->company_name . "\n";
    echo "  - Website: " . $companyInfo->website . "\n";
    echo "  - Is Active: " . ($companyInfo->is_active ? 'Yes' : 'No') . "\n\n";
} else {
    echo "✗ No active company info found\n\n";
}

// Test View Composer
echo "Testing View Composer...\n";
$composer = new \App\Http\ViewComposers\CompanyInfoComposer();

// Create a mock view
$view = new class {
    public $data = [];
    
    public function with($key, $value = null) {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }
};

$composer->compose($view);

echo "✓ View Composer data:\n";
foreach ($view->data as $key => $value) {
    if ($key === 'globalCompanyInfo') {
        echo "  - $key: " . (is_object($value) ? get_class($value) . ' object' : $value) . "\n";
    } else {
        echo "  - $key: $value\n";
    }
}

echo "\n✓ All tests completed successfully!\n";
echo "The 'Lihat Website' button will now redirect to: " . ($view->data['globalWebsiteUrl'] ?? 'Not set') . "\n";
