<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Book;

$coverMappings = [
    'The Great Gatsby' => 'storage/covers/the great gatsby.jpeg',
    'To Kill a Mockingbird' => 'storage/covers/to kill a mockingbird.jpeg',
    'Pride and Prejudice' => 'storage/covers/prideandprjudice.jpeg',
    'The 7 Habits of Highly Effective People' => 'storage/covers/the 7 habits of highly effevctive people.jpeg',
];

foreach ($coverMappings as $bookTitle => $coverPath) {
    $book = Book::where('title', $bookTitle)->first();
    
    if ($book) {
        $book->cover_image = $coverPath;
        $book->save();
        echo "✓ Updated: {$book->title}\n";
    } else {
        echo "✗ Not found: {$bookTitle}\n";
    }
}

echo "\nDone!\n";
