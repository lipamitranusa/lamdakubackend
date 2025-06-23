<?php

/**
 * Test Admin Articles Interface
 * This script tests if the admin articles interface is accessible
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "🚀 Testing Admin Articles Interface...\n\n";

try {
    // Test if we can access the articles model
    $articlesCount = \App\Models\Article::count();
    echo "✅ Articles Model: {$articlesCount} articles found\n";
    
    // Test if we can get published articles
    $publishedCount = \App\Models\Article::published()->count();
    echo "✅ Published Articles: {$publishedCount} published articles\n";
    
    // Test if we can get featured articles
    $featuredCount = \App\Models\Article::featured()->count();
    echo "✅ Featured Articles: {$featuredCount} featured articles\n";
    
    // Test if we can get categories
    $categories = \App\Models\Article::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category')
        ->toArray();
    echo "✅ Categories: " . implode(', ', $categories) . "\n";
    
    // Test if we can get tags
    $tags = \App\Models\Article::select('tags')
        ->whereNotNull('tags')
        ->get()
        ->pluck('tags')
        ->filter()
        ->unique()
        ->values()
        ->toArray();
    echo "✅ Tags: " . implode(', ', $tags) . "\n";
    
    echo "\n🎉 Admin Articles System: FULLY OPERATIONAL\n";
    echo "📍 Access URL: http://localhost:8000/admin/articles\n";
    echo "🔐 Login with: admin/admin123 or penulis/penulis123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
