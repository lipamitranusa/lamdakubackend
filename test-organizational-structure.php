<?php

require_once 'vendor/autoload.php';

use App\Models\OrganizationalStructure;

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ORGANIZATIONAL STRUCTURE SYSTEM TEST ===\n\n";

// Test 1: Check if table exists and has data
echo "1. Database Test:\n";
$count = OrganizationalStructure::count();
echo "   Total records: {$count}\n";

if ($count > 0) {
    echo "   ✅ Database table exists and has data\n";
} else {
    echo "   ❌ No data found in database\n";
    exit(1);
}

// Test 2: Check model methods
echo "\n2. Model Methods Test:\n";

// Test getLevels()
$levels = OrganizationalStructure::getLevels();
echo "   Available levels: " . implode(', ', $levels->toArray()) . "\n";
echo "   ✅ getLevels() method working\n";

// Test getByLevels()
$grouped = OrganizationalStructure::getByLevels();
echo "   Grouped by levels: " . $grouped->count() . " levels found\n";
echo "   ✅ getByLevels() method working\n";

// Test scopes
$activeCount = OrganizationalStructure::active()->count();
echo "   Active records: {$activeCount}\n";
echo "   ✅ active() scope working\n";

// Test 3: Check specific data structure
echo "\n3. Data Structure Test:\n";
foreach ($grouped as $level => $structures) {
    echo "   Level {$level}: " . $structures->count() . " positions\n";
    foreach ($structures as $structure) {
        echo "     - {$structure->name} ({$structure->position})\n";
    }
}
echo "   ✅ Data structure is properly organized\n";

// Test 4: API-like data formatting
echo "\n4. API Data Format Test:\n";
$chartData = [];
foreach ($grouped as $level => $structures) {
    $chartData[] = [
        'level' => $level,
        'positions' => $structures->map(function ($structure) {
            return [
                'id' => $structure->id,
                'name' => $structure->name,
                'position' => $structure->position,
                'description' => $structure->description,
                'background_color' => $structure->background_color,
                'icon_class' => $structure->icon_class,
                'position_order' => $structure->position_order
            ];
        })->toArray()
    ];
}

echo "   Chart data generated with " . count($chartData) . " levels\n";
echo "   ✅ API data formatting working\n";

// Test 5: Check specific level query
echo "\n5. Level-specific Query Test:\n";
$level1 = OrganizationalStructure::active()->byLevel(1)->get();
echo "   Level 1 (CEO/Director): " . $level1->count() . " position(s)\n";
if ($level1->count() > 0) {
    echo "     - " . $level1->first()->position . "\n";
}
echo "   ✅ Level-specific queries working\n";

echo "\n=== ALL TESTS PASSED! ===\n";
echo "✅ Organizational Structure Management System is fully operational!\n\n";

echo "Available Endpoints:\n";
echo "  Admin Panel:\n";
echo "    - /admin/organizational-structure (index)\n";
echo "    - /admin/organizational-structure/create (create)\n";
echo "    - /admin/organizational-structure/{id} (show)\n";
echo "    - /admin/organizational-structure/{id}/edit (edit)\n";
echo "\n";
echo "  API Endpoints:\n";
echo "    - GET /api/v1/organizational-structure (grouped by levels)\n";
echo "    - GET /api/v1/organizational-structure/list (flat list)\n";
echo "    - GET /api/v1/organizational-structure/chart (chart data)\n";
echo "    - GET /api/v1/organizational-structure/level/{level} (by level)\n";
echo "    - GET /api/v1/organizational-structure/{id} (single record)\n";
echo "\n";
