<?php

/**
 * Debug Article List Issue
 * Check why articles are not showing in admin interface
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "🔍 Debugging Article List Issue...\n\n";

try {
    // 1. Check total articles in database
    echo "1️⃣ Database Check:\n";
    $totalArticles = \App\Models\Article::count();
    echo "   Total Articles: {$totalArticles}\n";
    
    $publishedArticles = \App\Models\Article::where('status', 'published')->count();
    echo "   Published Articles: {$publishedArticles}\n";
    
    $draftArticles = \App\Models\Article::where('status', 'draft')->count();
    echo "   Draft Articles: {$draftArticles}\n";
    
    // 2. Check articles with authors
    echo "\n2️⃣ Articles with Authors:\n";
    $articlesWithAuthors = \App\Models\Article::with('author')->get();
    foreach ($articlesWithAuthors as $article) {
        $authorName = $article->author ? $article->author->name : 'No Author';
        echo "   - {$article->title} (Author ID: {$article->author_id}, Author: {$authorName}, Status: {$article->status})\n";
    }
    
    // 3. Check users
    echo "\n3️⃣ Users Check:\n";
    $users = \App\Models\User::all();
    foreach ($users as $user) {
        $articlesCount = $user->articles()->count();
        echo "   - {$user->name} (ID: {$user->id}, Role: {$user->role}, Articles: {$articlesCount})\n";
    }
    
    // 4. Test getCurrentUser method logic
    echo "\n4️⃣ Session Check (simulated):\n";
    
    // Simulate admin session
    session(['admin_user_id' => 1, 'admin_role' => 'admin', 'admin_authenticated' => true]);
    
    $userId = session('admin_user_id');
    $userRole = session('admin_role');
    echo "   Session User ID: {$userId}\n";
    echo "   Session User Role: {$userRole}\n";
    
    if ($userId) {
        $user = \App\Models\User::find($userId);
        if ($user) {
            echo "   Session User Found: {$user->name} ({$user->role})\n";
            
            // Test query that would be used in ArticleController
            $query = \App\Models\Article::with('author');
            
            // Check if user is penulis (which would filter articles)
            if ($user->role === 'penulis') {
                echo "   Applying Penulis filter (own articles only)\n";
                $query->where('author_id', $user->id);
            } else {
                echo "   Admin access - showing all articles\n";
            }
            
            $filteredArticles = $query->get();
            echo "   Articles visible to this user: " . $filteredArticles->count() . "\n";
            
            foreach ($filteredArticles as $article) {
                echo "     - {$article->title} (Status: {$article->status})\n";
            }
        } else {
            echo "   ❌ User not found with ID: {$userId}\n";
        }
    } else {
        echo "   ❌ No user ID in session\n";
    }
    
    // 5. Test with penulis user
    echo "\n5️⃣ Testing with Penulis User:\n";
    
    $penulisUser = \App\Models\User::where('role', 'penulis')->first();
    if ($penulisUser) {
        echo "   Penulis User: {$penulisUser->name} (ID: {$penulisUser->id})\n";
        
        // Simulate penulis session
        session(['admin_user_id' => $penulisUser->id, 'admin_role' => 'penulis']);
        
        $query = \App\Models\Article::with('author');
        $query->where('author_id', $penulisUser->id);
        
        $penulisArticles = $query->get();
        echo "   Articles for this penulis: " . $penulisArticles->count() . "\n";
        
        foreach ($penulisArticles as $article) {
            echo "     - {$article->title} (Status: {$article->status})\n";
        }
    } else {
        echo "   ❌ No penulis user found\n";
    }
    
    echo "\n🎯 Summary:\n";
    echo "   If you see 0 articles for a user, the issue is likely:\n";
    echo "   1. Session not properly set in browser\n";
    echo "   2. User role filtering is too restrictive\n";
    echo "   3. Articles have wrong author_id values\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
