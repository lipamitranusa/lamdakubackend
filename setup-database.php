<?php
echo "=== DATABASE SETUP FOR LAMDAKU CMS ===\n\n";

// Check if .env exists and has DB credentials
if (!file_exists(".env")) {
    echo "❌ .env file not found!\n";
    exit(1);
}

$env = file_get_contents(".env");
if (strpos($env, "Lamdaku25$") !== false) {
    echo "❌ Please update database password in .env file first!\n";
    echo "Update this line: DB_PASSWORD=Lamdaku25$\n";
    exit(1);
}

// Bootstrap Laravel
require_once "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Console\Kernel");
$kernel->bootstrap();

try {
    // Test database connection
    $pdo = $app->make("db")->connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Run migrations
    echo "\n🔄 Running migrations...\n";
    $output = [];
    $return_var = 0;
    exec("php artisan migrate --force 2>&1", $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ Migrations completed successfully\n";
        foreach ($output as $line) {
            echo "   {$line}\n";
        }
        
        // Run seeders
        echo "\n🌱 Running seeders...\n";
        exec("php artisan db:seed --force 2>&1", $output, $return_var);
        
        if ($return_var === 0) {
            echo "✅ Seeders completed successfully\n";
        } else {
            echo "⚠️ Seeders completed with warnings\n";
        }
        
    } else {
        echo "❌ Migration failed\n";
        foreach ($output as $line) {
            echo "   {$line}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "\nPlease check your database credentials in .env file\n";
}
?>
