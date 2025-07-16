<?php
/**
 * LAMDAKU CMS Fresh Deployment Script (PHP Version)
 * For servers without bash/shell access
 * Usage: php fresh-deploy.php
 */

echo "🚀 LAMDAKU CMS FRESH DEPLOYMENT (PHP VERSION)\n";
echo str_repeat("=", 50) . "\n";

// Configuration
$config = [
    'domain' => 'lamdaku.com',
    'repo_url' => 'https://github.com/lipamitranusa/lamdakubackend.git',
    'backup_retention_days' => 7,
    'timestamp' => date('Ymd_His')
];

// Helper functions
function log_info($message) {
    echo "ℹ️  $message\n";
}

function log_success($message) {
    echo "✅ $message\n";
}

function log_warning($message) {
    echo "⚠️  $message\n";
}

function log_error($message) {
    echo "❌ $message\n";
}

function check_requirements() {
    log_info("Checking system requirements...");
    
    $checks = [
        'PHP Version' => version_compare(PHP_VERSION, '7.4.0', '>='),
        'OpenSSL' => extension_loaded('openssl'),
        'PDO' => extension_loaded('pdo'),
        'Mbstring' => extension_loaded('mbstring'),
        'Tokenizer' => extension_loaded('tokenizer'),
        'JSON' => extension_loaded('json'),
        'cURL' => extension_loaded('curl'),
    ];
    
    $failed = [];
    foreach ($checks as $name => $status) {
        if ($status) {
            log_success("$name: OK");
        } else {
            log_error("$name: MISSING");
            $failed[] = $name;
        }
    }
    
    if (!empty($failed)) {
        log_error("Missing requirements: " . implode(', ', $failed));
        return false;
    }
    
    return true;
}

function create_backup() {
    global $config;
    
    log_info("Creating backup...");
    
    $backupDir = "../backups/backup_{$config['timestamp']}";
    
    if (!is_dir('../backups')) {
        mkdir('../backups', 0755, true);
    }
    
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    // Backup files
    $files = glob('*');
    $hiddenFiles = glob('.*');
    $allFiles = array_merge($files, $hiddenFiles);
    
    foreach ($allFiles as $file) {
        if ($file === '.' || $file === '..' || $file === basename(__FILE__)) {
            continue;
        }
        
        if (is_dir($file)) {
            system("cp -r '$file' '$backupDir/'");
        } else {
            copy($file, "$backupDir/$file");
        }
    }
    
    // Backup database
    if (file_exists('.env')) {
        $env = parse_ini_file('.env');
        if (isset($env['DB_DATABASE']) && isset($env['DB_USERNAME']) && isset($env['DB_PASSWORD'])) {
            if ($env['DB_PASSWORD'] !== 'YOUR_DATABASE_PASSWORD_HERE') {
                $dbBackup = "$backupDir/database_{$config['timestamp']}.sql";
                $cmd = "mysqldump -u {$env['DB_USERNAME']} -p{$env['DB_PASSWORD']} {$env['DB_DATABASE']} > $dbBackup 2>/dev/null";
                $result = system($cmd, $returnCode);
                if ($returnCode === 0) {
                    log_success("Database backup created");
                } else {
                    log_warning("Database backup failed");
                }
            }
        }
    }
    
    log_success("Backup created: $backupDir");
    return $backupDir;
}

function clean_directory() {
    log_info("Cleaning installation directory...");
    
    $files = glob('*');
    $hiddenFiles = glob('.*');
    $allFiles = array_merge($files, $hiddenFiles);
    
    foreach ($allFiles as $file) {
        if ($file === '.' || $file === '..' || $file === basename(__FILE__)) {
            continue;
        }
        
        if (is_dir($file)) {
            system("rm -rf '$file'");
        } else {
            unlink($file);
        }
    }
    
    log_success("Directory cleaned");
}

function download_project() {
    global $config;
    
    log_info("Downloading latest project...");
    
    // Try git first
    if (system('which git', $returnCode) && $returnCode === 0) {
        log_info("Using git clone...");
        $result = system("git clone {$config['repo_url']} . 2>&1", $returnCode);
        if ($returnCode === 0) {
            log_success("Project downloaded via git");
            return true;
        }
    }
    
    // Fallback to wget/curl
    log_info("Git not available, trying wget...");
    $zipUrl = str_replace('.git', '/archive/refs/heads/main.zip', $config['repo_url']);
    
    if (function_exists('exec')) {
        exec("wget -q '$zipUrl' -O project.zip", $output, $returnCode);
        if ($returnCode === 0 && file_exists('project.zip')) {
            exec('unzip -q project.zip');
            exec('mv lamdakubackend-main/* .');
            exec('mv lamdakubackend-main/.* . 2>/dev/null || true');
            exec('rm -rf lamdakubackend-main project.zip');
            log_success("Project downloaded via wget");
            return true;
        }
    }
    
    // Fallback to cURL
    log_info("Trying cURL...");
    $zipContent = file_get_contents($zipUrl);
    if ($zipContent !== false) {
        file_put_contents('project.zip', $zipContent);
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive;
            if ($zip->open('project.zip') === TRUE) {
                $zip->extractTo('.');
                $zip->close();
                system('mv lamdakubackend-main/* .');
                system('mv lamdakubackend-main/.* . 2>/dev/null || true');
                system('rm -rf lamdakubackend-main project.zip');
                log_success("Project downloaded via cURL");
                return true;
            }
        }
    }
    
    log_error("Failed to download project");
    return false;
}

function set_permissions() {
    log_info("Setting file permissions...");
    
    if (file_exists('artisan')) {
        chmod('artisan', 0755);
    }
    
    $dirs = ['storage', 'bootstrap/cache'];
    foreach ($dirs as $dir) {
        if (is_dir($dir)) {
            chmod($dir, 0755);
            system("find $dir -type d -exec chmod 755 {} \\; 2>/dev/null || true");
            system("find $dir -type f -exec chmod 644 {} \\; 2>/dev/null || true");
        }
    }
    
    log_success("Permissions set");
}

function setup_environment($backupDir = null) {
    log_info("Setting up environment...");
    
    // Copy .env file
    if (file_exists('.env.production')) {
        copy('.env.production', '.env');
        log_success(".env created from .env.production");
    } elseif (file_exists('.env.example')) {
        copy('.env.example', '.env');
        log_success(".env created from .env.example");
    } else {
        log_error(".env template not found");
        return false;
    }
    
    // Restore database password from backup
    if ($backupDir && file_exists("$backupDir/.env")) {
        $backupEnv = file_get_contents("$backupDir/.env");
        if (preg_match('/DB_PASSWORD=(.+)/', $backupEnv, $matches)) {
            $dbPassword = trim($matches[1]);
            if ($dbPassword !== 'YOUR_DATABASE_PASSWORD_HERE') {
                $envContent = file_get_contents('.env');
                $envContent = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD=$dbPassword", $envContent);
                file_put_contents('.env', $envContent);
                log_success("Database password restored from backup");
            }
        }
    }
    
    chmod('.env', 0644);
    return true;
}

function install_dependencies() {
    log_info("Installing dependencies...");
    
    if (system('which composer', $returnCode) && $returnCode === 0) {
        log_info("Installing via Composer...");
        system('composer install --no-dev --optimize-autoloader --no-interaction 2>&1', $returnCode);
        if ($returnCode === 0) {
            log_success("Dependencies installed successfully");
            return true;
        } else {
            log_warning("Composer install failed, trying with ignore platform reqs...");
            system('composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction 2>&1', $returnCode);
            if ($returnCode === 0) {
                log_success("Dependencies installed with ignore platform reqs");
                return true;
            }
        }
    }
    
    log_warning("Composer not available or failed");
    log_info("You'll need to upload vendor/ folder manually");
    return false;
}

function setup_laravel() {
    log_info("Setting up Laravel application...");
    
    // Test artisan
    if (!file_exists('vendor/autoload.php')) {
        log_warning("vendor/autoload.php not found - skipping Laravel setup");
        return false;
    }
    
    // Generate key if needed
    $envContent = file_get_contents('.env');
    if (!preg_match('/APP_KEY=base64:/', $envContent)) {
        log_info("Generating application key...");
        system('php artisan key:generate --force 2>&1');
        log_success("Application key generated");
    }
    
    // Clear caches
    log_info("Clearing caches...");
    system('php artisan cache:clear 2>/dev/null || true');
    system('php artisan config:clear 2>/dev/null || true');
    system('php artisan route:clear 2>/dev/null || true');
    system('php artisan view:clear 2>/dev/null || true');
    
    // Create storage link
    if (!file_exists('public/storage')) {
        log_info("Creating storage link...");
        system('php artisan storage:link 2>/dev/null || true');
        log_success("Storage link created");
    }
    
    // Test database connection
    $env = parse_ini_file('.env');
    if (isset($env['DB_PASSWORD']) && $env['DB_PASSWORD'] !== 'YOUR_DATABASE_PASSWORD_HERE') {
        log_info("Testing database connection...");
        $output = system('php artisan migrate:status 2>&1', $returnCode);
        if ($returnCode === 0) {
            log_success("Database connection successful");
            
            // Ask about migrations
            echo "Run fresh migrations? This will DESTROY existing data! (y/N): ";
            $response = trim(fgets(STDIN));
            if (strtolower($response) === 'y') {
                log_warning("Running fresh migrations...");
                system('php artisan migrate:fresh --seed --force 2>&1');
                log_success("Fresh migrations completed");
            } else {
                log_info("Running safe migrations...");
                system('php artisan migrate --force 2>&1');
                log_success("Migrations completed");
            }
        } else {
            log_warning("Database connection failed - check credentials in .env");
        }
    } else {
        log_warning("Database password not set - please update .env file");
    }
    
    // Cache for production
    log_info("Caching for production...");
    system('php artisan config:cache 2>/dev/null || true');
    system('php artisan route:cache 2>/dev/null || true');
    
    return true;
}

function verify_installation() {
    global $config;
    
    log_info("Verifying installation...");
    
    // Check file structure
    $requiredFiles = ['artisan', 'composer.json', '.env', 'app', 'config', 'database', 'public'];
    $missingFiles = [];
    
    foreach ($requiredFiles as $file) {
        if (!file_exists($file)) {
            $missingFiles[] = $file;
        }
    }
    
    if (empty($missingFiles)) {
        log_success("All required files present");
    } else {
        log_error("Missing files: " . implode(', ', $missingFiles));
        return false;
    }
    
    // Test web response
    log_info("Testing web response...");
    $url = "https://{$config['domain']}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 || $httpCode === 301 || $httpCode === 302) {
        log_success("Website responding (HTTP $httpCode)");
    } else {
        log_warning("Website not responding properly (HTTP $httpCode)");
    }
    
    return true;
}

function cleanup() {
    log_info("Cleaning up...");
    
    // Remove deployment script
    if (file_exists(basename(__FILE__))) {
        unlink(basename(__FILE__));
        log_success("Deployment script cleaned up");
    }
}

function print_summary() {
    global $config;
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 DEPLOYMENT COMPLETED!\n";
    echo str_repeat("=", 50) . "\n\n";
    
    echo "📊 DEPLOYMENT SUMMARY:\n";
    echo "  • Project downloaded from: {$config['repo_url']}\n";
    echo "  • Environment: Production\n";
    echo "  • Domain: https://{$config['domain']}\n";
    echo "  • Timestamp: {$config['timestamp']}\n\n";
    
    echo "📋 NEXT STEPS:\n";
    echo "  1. 🔧 Update database password in .env (if needed)\n";
    echo "  2. 🌐 Test website: https://{$config['domain']}\n";
    echo "  3. 🔍 Test API: https://{$config['domain']}/api\n";
    echo "  4. 👤 Test admin: https://{$config['domain']}/admin\n";
    echo "  5. 📝 Check logs: tail -f storage/logs/laravel.log\n\n";
    
    echo "🔧 TROUBLESHOOTING:\n";
    echo "  • Check .env file for correct database credentials\n";
    echo "  • Verify file permissions (storage/, bootstrap/cache/)\n";
    echo "  • Check server error logs in cPanel\n";
    echo "  • Run: php artisan migrate if database issues\n\n";
}

// Main deployment process
try {
    log_info("Starting deployment process...");
    
    // Step 1: Check requirements
    if (!check_requirements()) {
        throw new Exception("System requirements not met");
    }
    
    // Step 2: Create backup
    $backupDir = create_backup();
    
    // Step 3: Clean directory
    clean_directory();
    
    // Step 4: Download project
    if (!download_project()) {
        throw new Exception("Failed to download project");
    }
    
    // Step 5: Set permissions
    set_permissions();
    
    // Step 6: Setup environment
    if (!setup_environment($backupDir)) {
        throw new Exception("Failed to setup environment");
    }
    
    // Step 7: Install dependencies
    install_dependencies();
    
    // Step 8: Setup Laravel
    setup_laravel();
    
    // Step 9: Verify installation
    verify_installation();
    
    // Step 10: Print summary
    print_summary();
    
    log_success("Fresh deployment completed successfully! 🚀");
    
} catch (Exception $e) {
    log_error("Deployment failed: " . $e->getMessage());
    echo "\nCheck the error above and try manual deployment steps.\n";
    echo "Refer to REDEPLOY_GUIDE.md for detailed instructions.\n";
    exit(1);
}
?>
