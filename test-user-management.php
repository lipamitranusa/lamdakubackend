<?php
// Test script untuk debug user management
session_start();

echo "<h2>User Management Debug Test</h2>";

// Simulate admin session
$_SESSION = [
    'admin_authenticated' => true,
    'admin_user_id' => 1,
    'admin_user' => 'Administrator',
    'admin_role' => 'admin'
];

echo "<h3>1. Session Check</h3>";
echo "Admin authenticated: " . (isset($_SESSION['admin_authenticated']) ? 'YES' : 'NO') . "<br>";
echo "Admin role: " . ($_SESSION['admin_role'] ?? 'NOT SET') . "<br>";

echo "<h3>2. Database Connection Test</h3>";
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    echo "Database connection: SUCCESS<br>";
    
    // Check users table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Users count: " . $result['count'] . "<br>";
    
    // Get sample users
    $stmt = $pdo->query("SELECT id, name, username, role, is_active FROM users LIMIT 3");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>3. Sample Users</h3>";
    foreach ($users as $user) {
        echo "ID: {$user['id']}, Name: {$user['name']}, Username: {$user['username']}, Role: {$user['role']}, Active: " . ($user['is_active'] ? 'YES' : 'NO') . "<br>";
    }
    
} catch (Exception $e) {
    echo "Database connection FAILED: " . $e->getMessage() . "<br>";
}

echo "<h3>4. Route Test</h3>";
echo "Try accessing: <a href='/admin/users'>/admin/users</a><br>";
echo "Try accessing: <a href='http://127.0.0.1:8000/admin/users'>http://127.0.0.1:8000/admin/users</a><br>";

echo "<h3>5. File Check</h3>";
$files_to_check = [
    'app/Http/Controllers/Admin/UserController.php',
    'resources/views/admin/users/index.blade.php',
    'resources/views/admin/layout-simple.blade.php'
];

foreach ($files_to_check as $file) {
    echo $file . ": " . (file_exists($file) ? 'EXISTS' : 'MISSING') . "<br>";
}
?>
