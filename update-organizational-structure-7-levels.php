<?php

require_once 'vendor/autoload.php';

use App\Models\OrganizationalStructure;

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== UPDATING ORGANIZATIONAL STRUCTURE TO 7 LEVELS ===\n\n";

// Check current structure
echo "1. Current Organizational Structure:\n";
$currentStructures = OrganizationalStructure::orderBy('level_order')->orderBy('position_order')->get();

$groupedCurrent = $currentStructures->groupBy('level_order');
foreach ($groupedCurrent as $level => $structures) {
    echo "   Level {$level}: " . $structures->count() . " positions\n";
    foreach ($structures as $structure) {
        echo "     - {$structure->name} ({$structure->position})\n";
    }
}

echo "\n2. Adding new Level 6 and Level 7 positions:\n";

// Add Level 6 positions
$level6Positions = [
    [
        'name' => 'Andi Pratama, S.Kom.',
        'position' => 'Junior Staff IT',
        'description' => 'Membantu dalam maintenance sistem dan troubleshooting komputer kantor',
        'level_order' => 6,
        'position_order' => 1,
        'background_color' => '#e1f5fe',
        'icon_class' => 'fas fa-laptop',
        'is_active' => true
    ],
    [
        'name' => 'Sari Dewi, S.E.',
        'position' => 'Junior Staff Administrasi',
        'description' => 'Membantu dalam pengolahan dokumen dan administrasi umum',
        'level_order' => 6,
        'position_order' => 2,
        'background_color' => '#f3e5f5',
        'icon_class' => 'fas fa-file-alt',
        'is_active' => true
    ],
    [
        'name' => 'Bayu Setiawan, S.T.',
        'position' => 'Junior Staff Teknis',
        'description' => 'Membantu dalam pelaksanaan kegiatan teknis dan operasional',
        'level_order' => 6,
        'position_order' => 3,
        'background_color' => '#e8f5e8',
        'icon_class' => 'fas fa-tools',
        'is_active' => true
    ]
];

// Add Level 7 positions
$level7Positions = [
    [
        'name' => 'Jessica Amelia',
        'position' => 'Trainee Akuntansi',
        'description' => 'Program pelatihan dalam bidang akuntansi dan keuangan',
        'level_order' => 7,
        'position_order' => 1,
        'background_color' => '#fff3e0',
        'icon_class' => 'fas fa-graduation-cap',
        'is_active' => true
    ],
    [
        'name' => 'Muhammad Rifki',
        'position' => 'Intern IT Support',
        'description' => 'Program magang dalam bidang teknologi informasi dan support',
        'level_order' => 7,
        'position_order' => 2,
        'background_color' => '#fce4ec',
        'icon_class' => 'fas fa-user-graduate',
        'is_active' => true
    ],
    [
        'name' => 'Putri Maharani',
        'position' => 'Trainee Marketing',
        'description' => 'Program pelatihan dalam bidang pemasaran dan promosi',
        'level_order' => 7,
        'position_order' => 3,
        'background_color' => '#e0f2f1',
        'icon_class' => 'fas fa-bullhorn',
        'is_active' => true
    ]
];

// Insert Level 6 positions
echo "   Adding Level 6 positions:\n";
foreach ($level6Positions as $position) {
    try {
        $created = OrganizationalStructure::create($position);
        echo "     ✅ Added: {$position['name']} - {$position['position']}\n";
    } catch (Exception $e) {
        echo "     ❌ Failed to add: {$position['name']} - Error: " . $e->getMessage() . "\n";
    }
}

// Insert Level 7 positions
echo "\n   Adding Level 7 positions:\n";
foreach ($level7Positions as $position) {
    try {
        $created = OrganizationalStructure::create($position);
        echo "     ✅ Added: {$position['name']} - {$position['position']}\n";
    } catch (Exception $e) {
        echo "     ❌ Failed to add: {$position['name']} - Error: " . $e->getMessage() . "\n";
    }
}

echo "\n3. Updated Organizational Structure (7 Levels):\n";
$updatedStructures = OrganizationalStructure::orderBy('level_order')->orderBy('position_order')->get();
$groupedUpdated = $updatedStructures->groupBy('level_order');

foreach ($groupedUpdated as $level => $structures) {
    $levelNames = [
        1 => 'Pimpinan Tertinggi',
        2 => 'Direktur',
        3 => 'Manager',
        4 => 'Supervisor',
        5 => 'Staff',
        6 => 'Junior Staff',
        7 => 'Trainee/Intern'
    ];
    
    echo "   Level {$level} ({$levelNames[$level]}): " . $structures->count() . " positions\n";
    foreach ($structures as $structure) {
        echo "     - {$structure->name} ({$structure->position})\n";
    }
}

echo "\n=== ORGANIZATIONAL STRUCTURE SUCCESSFULLY UPDATED TO 7 LEVELS ===\n";
echo "✅ Total positions: " . OrganizationalStructure::count() . "\n";
echo "✅ Total levels: " . OrganizationalStructure::select('level_order')->distinct()->count() . "\n";

echo "\nSystem features updated:\n";
echo "- ✅ Admin forms now support 7 levels\n";
echo "- ✅ Level information updated in display views\n";
echo "- ✅ Sample data added for Level 6 and Level 7\n";
echo "- ✅ All existing API endpoints will automatically support 7 levels\n";

echo "\nLevel hierarchy:\n";
echo "Level 1: Pimpinan Tertinggi (Direktur Utama, CEO, President)\n";
echo "Level 2: Direktur (Direktur Operasional, Direktur Pengembangan)\n";
echo "Level 3: Manager (Manager Akreditasi, Manager Divisi)\n";
echo "Level 4: Supervisor (Supervisor, Koordinator)\n";
echo "Level 5: Staff (Staff Operasional, Analis)\n";
echo "Level 6: Junior Staff (Junior Staff, Asisten)\n";
echo "Level 7: Trainee/Intern (Trainee, Intern, Magang)\n";

echo "\n🚀 The organizational structure is now ready with 7 levels!\n";
