<?php

require_once 'vendor/autoload.php';

use App\Models\OrganizationalStructure;

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ADDING LEVEL 5 STAFF POSITIONS ===\n\n";

// Add Level 5 positions to complete the hierarchy
$level5Positions = [
    [
        'name' => 'Maya Indah, S.E.',
        'position' => 'Staff Administrasi',
        'description' => 'Mengelola administrasi umum dan dokumentasi perusahaan',
        'level_order' => 5,
        'position_order' => 1,
        'background_color' => '#f8f9fa',
        'icon_class' => 'fas fa-user-tie',
        'is_active' => true
    ],
    [
        'name' => 'Rizki Pratama, S.T.',
        'position' => 'Staff Teknis',
        'description' => 'Melaksanakan kegiatan teknis operasional sehari-hari',
        'level_order' => 5,
        'position_order' => 2,
        'background_color' => '#fff3cd',
        'icon_class' => 'fas fa-cogs',
        'is_active' => true
    ],
    [
        'name' => 'Nurul Fitria, S.Ak.',
        'position' => 'Staff Keuangan',
        'description' => 'Mengelola transaksi keuangan dan laporan harian',
        'level_order' => 5,
        'position_order' => 3,
        'background_color' => '#d1ecf1',
        'icon_class' => 'fas fa-calculator',
        'is_active' => true
    ]
];

echo "Adding Level 5 (Staff) positions:\n";
foreach ($level5Positions as $position) {
    try {
        $created = OrganizationalStructure::create($position);
        echo "  ✅ Added: {$position['name']} - {$position['position']}\n";
    } catch (Exception $e) {
        echo "  ❌ Failed to add: {$position['name']} - Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== COMPLETE 7-LEVEL ORGANIZATIONAL STRUCTURE ===\n";
$allStructures = OrganizationalStructure::orderBy('level_order')->orderBy('position_order')->get();
$grouped = $allStructures->groupBy('level_order');

$levelNames = [
    1 => 'Pimpinan Tertinggi',
    2 => 'Direktur',
    3 => 'Manager',
    4 => 'Supervisor', 
    5 => 'Staff',
    6 => 'Junior Staff',
    7 => 'Trainee/Intern'
];

foreach ($grouped as $level => $structures) {
    echo "\nLevel {$level} ({$levelNames[$level]}): " . $structures->count() . " positions\n";
    foreach ($structures as $structure) {
        echo "  - {$structure->name} ({$structure->position})\n";
    }
}

echo "\n✅ ORGANIZATIONAL STRUCTURE COMPLETE WITH 7 LEVELS!\n";
echo "Total positions: " . OrganizationalStructure::count() . "\n";
echo "Total active levels: " . count($grouped) . "\n";
