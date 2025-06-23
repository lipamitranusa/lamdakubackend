<?php

/**
 * Update Article Authors for Testing
 * Script ini akan mengupdate beberapa artikel agar ditulis oleh user 'penulis'
 * sehingga bisa terlihat ketika login sebagai penulis
 */

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "🔧 Updating Article Authors for Role Testing...\n\n";

try {
    // Cari user penulis
    $penulisUser = \App\Models\User::where('username', 'penulis')->first();
    
    if (!$penulisUser) {
        echo "❌ User 'penulis' tidak ditemukan\n";
        echo "💡 Pastikan user 'penulis' sudah ada di database\n";
        exit(1);
    }
    
    echo "📝 User penulis ditemukan: {$penulisUser->name} (ID: {$penulisUser->id})\n\n";
    
    // Update 3 artikel pertama agar ditulis oleh penulis
    $articlesToUpdate = \App\Models\Article::take(3)->get();
    
    if ($articlesToUpdate->isEmpty()) {
        echo "❌ Tidak ada artikel untuk diupdate\n";
        exit(1);
    }
    
    $updated = 0;
    foreach ($articlesToUpdate as $article) {
        $article->update(['author_id' => $penulisUser->id]);
        echo "✅ Updated: '{$article->title}' -> Author: {$penulisUser->name}\n";
        $updated++;
    }
    
    echo "\n📊 Summary:\n";
    echo "   ✅ {$updated} artikel berhasil diupdate\n";
    echo "   📝 Artikel ini sekarang bisa dilihat oleh user 'penulis'\n";
    echo "   👑 Admin tetap bisa melihat semua artikel\n\n";
    
    // Tampilkan statistik
    $totalArticles = \App\Models\Article::count();
    $penulisArticles = \App\Models\Article::where('author_id', $penulisUser->id)->count();
    $adminArticles = \App\Models\Article::where('author_id', '!=', $penulisUser->id)->count();
    
    echo "📈 Article Statistics:\n";
    echo "   📚 Total artikel: {$totalArticles}\n";
    echo "   ✍️ Artikel penulis: {$penulisArticles}\n";
    echo "   👑 Artikel admin/lainnya: {$adminArticles}\n\n";
    
    echo "🎉 Update selesai!\n";
    echo "💡 Sekarang login sebagai 'penulis' untuk melihat artikelnya\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
