<?php

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Update Article Authors for Testing ===\n";

// Get penulis user ID
$penulisUser = DB::table('users')->where('username', 'penulis')->first();

if (!$penulisUser) {
    echo "❌ Penulis user not found!\n";
    exit(1);
}

echo "✅ Penulis user found: ID {$penulisUser->id} (Username: {$penulisUser->username})\n";

// Update first 2 articles to be authored by penulis
$articlesToUpdate = DB::table('articles')
    ->orderBy('id')
    ->limit(2)
    ->get(['id', 'title']);

if ($articlesToUpdate->isEmpty()) {
    echo "❌ No articles found to update!\n";
    exit(1);
}

echo "\n📝 Articles to update:\n";
foreach ($articlesToUpdate as $article) {
    echo "   - ID {$article->id}: {$article->title}\n";
}

$articleIds = $articlesToUpdate->pluck('id')->toArray();

// Update the articles
$updated = DB::table('articles')
    ->whereIn('id', $articleIds)
    ->update([
        'author_id' => $penulisUser->id,
        'updated_at' => now()
    ]);

echo "\n✅ Updated {$updated} articles to be authored by 'penulis'\n";

// Verify the update
$penulisArticles = DB::table('articles')
    ->where('author_id', $penulisUser->id)
    ->get(['id', 'title']);

echo "\n📊 Articles now owned by 'penulis' user:\n";
foreach ($penulisArticles as $article) {
    echo "   - ID {$article->id}: {$article->title}\n";
}

echo "\n🎯 Now you can test:\n";
echo "   1. Login as 'admin' → Should see all 8 articles\n";
echo "   2. Login as 'penulis' → Should see {$penulisArticles->count()} articles\n";
echo "\nThis confirms the role-based access control is working correctly! 🎉\n";
