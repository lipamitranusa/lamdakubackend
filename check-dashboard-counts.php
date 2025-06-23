<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DASHBOARD DATABASE COUNTS ===\n";
echo "Contacts: " . App\Models\Contact::count() . "\n";
echo "Services: " . App\Models\Service::count() . "\n";
echo "Articles: " . App\Models\Article::count() . "\n";
echo "Events: " . App\Models\Event::count() . "\n";
echo "Pages: " . App\Models\Page::count() . "\n";
echo "Timelines: " . App\Models\Timeline::count() . "\n";
echo "Users: " . App\Models\User::count() . "\n";

echo "\n=== UNREAD CONTACTS ===\n";
echo "Unread Contacts: " . App\Models\Contact::where('is_read', false)->count() . "\n";

echo "\n=== RECENT CONTACTS ===\n";
$recentContacts = App\Models\Contact::orderBy('created_at', 'desc')->take(3)->get();
foreach ($recentContacts as $contact) {
    echo "- {$contact->name} ({$contact->email}) - " . $contact->created_at->format('Y-m-d H:i') . "\n";
}

echo "\nDone!\n";
