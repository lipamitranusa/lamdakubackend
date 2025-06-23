<?php

echo "<h1>Dashboard Content Debug Test</h1>";

// Test 1: Basic PHP functionality
echo "<h2>Test 1: PHP Working</h2>";
echo "<p>✅ PHP is working correctly</p>";
echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";

// Test 2: Session data
echo "<h2>Test 2: Session Data</h2>";
session_start();
echo "<pre>";
echo "Admin authenticated: " . (session('admin_authenticated') ? 'Yes' : 'No') . "\n";
echo "Admin user: " . (session('admin_user') ? session('admin_user') : 'Not set') . "\n";
echo "Admin ID: " . (session('admin_id') ? session('admin_id') : 'Not set') . "\n";
echo "Admin role: " . (session('admin_role') ? session('admin_role') : 'Not set') . "\n";
echo "</pre>";

// Test 3: Laravel paths
echo "<h2>Test 3: Laravel Framework</h2>";
require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "<p>✅ Laravel bootstrap loaded</p>";
    
    // Test database connection
    $pdo = new PDO('mysql:host=localhost;dbname=lamdaku_cms', 'root', '');
    echo "<p>✅ Database connection successful</p>";
    
    // Test simple query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM pages");
    $result = $stmt->fetch();
    echo "<p>✅ Pages count: " . $result['count'] . "</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

// Test 4: Simple dashboard content
echo "<h2>Test 4: Simple Dashboard Content</h2>";
echo '<div style="margin: 20px; padding: 20px; background: #f8f9fc; border-radius: 8px;">
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <div style="background: #007bff; color: white; padding: 20px; border-radius: 8px;">
            <h3>Sales</h3>
            <h2>Rp 5.000.000</h2>
            <small>January - June 2025</small>
        </div>
        <div style="background: #17a2b8; color: white; padding: 20px; border-radius: 8px;">
            <h3>Traffic</h3>
            <h2>45.000</h2>
            <small>January - December 2024</small>
        </div>
        <div style="background: #ffc107; color: white; padding: 20px; border-radius: 8px;">
            <h3>Customers</h3>
            <h2>107.845</h2>
            <small>(-12.4% ↓)</small>
        </div>
        <div style="background: #28a745; color: white; padding: 20px; border-radius: 8px;">
            <h3>Orders</h3>
            <h2>985</h2>
            <small>(17.2% ↑)</small>
        </div>
    </div>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3>Dashboard Test Content</h3>
        <p>This is a simple test to verify that content can be displayed in the dashboard area.</p>
        <p>If you can see this content with proper styling, then the dashboard layout is working correctly.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">User</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Status</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6;">Activity</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #dee2e6;">John Doe</td>
                    <td style="padding: 12px; border-bottom: 1px solid #dee2e6;"><span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Active</span></td>
                    <td style="padding: 12px; border-bottom: 1px solid #dee2e6;">2 minutes ago</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #dee2e6;">Jane Smith</td>
                    <td style="padding: 12px; border-bottom: 1px solid #dee2e6;"><span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Online</span></td>
                    <td style="padding: 12px; border-bottom: 1px solid #dee2e6;">5 minutes ago</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>';

echo "<h2>Test Complete</h2>";
echo "<p>If you can see all the content above with proper styling, the dashboard should work correctly.</p>";
echo "<p><a href='/admin/dashboard' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Go to Dashboard</a></p>";

?>
