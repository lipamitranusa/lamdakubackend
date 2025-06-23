<?php

/**
 * Test Article Controller Query
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "🧪 Testing Article Controller Query Logic...\n\n";

// Simulate being logged in as admin
session(['admin_authenticated' => true, 'admin_user_id' => 1, 'admin_role' => 'admin']);

echo "1️⃣ Session Data:\n";
echo "   admin_authenticated: " . (session('admin_authenticated') ? 'true' : 'false') . "\n";
echo "   admin_user_id: " . session('admin_user_id') . "\n";
echo "   admin_role: " . session('admin_role') . "\n\n";

echo "2️⃣ Testing getCurrentUser logic:\n";
$userId = session('admin_user_id');
if (!$userId) {
    echo "   ❌ No user ID in session\n";
} else {
    echo "   ✅ User ID found: {$userId}\n";
    
    $user = \App\Models\User::find($userId);
    if (!$user) {
        echo "   ❌ User not found in database\n";
    } else {
        echo "   ✅ User found: {$user->name} ({$user->role})\n";
        
        echo "\n3️⃣ Testing Article Query:\n";
        $query = \App\Models\Article::with('author');
        
        // Check role-based filtering
        if ($user->role === 'penulis') {
            echo "   Applying penulis filter - only own articles\n";
            $query->where('author_id', $user->id);
        } else {
            echo "   Admin access - showing all articles\n";
        }
        
        $articles = $query->get();
        echo "   Articles found: " . $articles->count() . "\n";
        
        echo "\n4️⃣ Article List:\n";
        foreach ($articles as $article) {
            $authorName = $article->author ? $article->author->name : 'Unknown';
            echo "   - {$article->title}\n";
            echo "     Author: {$authorName} (ID: {$article->author_id})\n";
            echo "     Status: {$article->status}\n";
            echo "     Created: {$article->created_at}\n\n";
        }
        
        if ($articles->count() === 0) {
            echo "   🔍 Debugging empty results:\n";
            
            // Check if it's role filtering
            $allArticles = \App\Models\Article::count();
            echo "   Total articles in DB: {$allArticles}\n";
            
            if ($user->role === 'penulis') {
                $userArticles = \App\Models\Article::where('author_id', $user->id)->count();
                echo "   Articles by this penulis: {$userArticles}\n";
                
                if ($userArticles === 0) {
                    echo "   ⚠️ Issue: This penulis has no articles!\n";
                    echo "   Solution: Create articles for this user or login as admin\n";
                }
            }
        }
    }
}

echo "\n🎯 Testing with Admin User:\n";
// Test with admin user specifically
$adminUser = \App\Models\User::where('role', 'admin')->first();
if ($adminUser) {
    echo "   Admin user: {$adminUser->name} (ID: {$adminUser->id})\n";
    
    session(['admin_user_id' => $adminUser->id, 'admin_role' => 'admin']);
    
    $adminQuery = \App\Models\Article::with('author');
    // Admin sees all articles, no filtering
    $adminArticles = $adminQuery->get();
    
    echo "   Articles visible to admin: " . $adminArticles->count() . "\n";
}
