<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;

class UpdateBookCovers extends Command
{
    protected $signature = 'app:update-book-covers';

    protected $description = 'Update book cover_image field dengan path yang sesuai';

    public function handle()
    {
        $coverMappings = [
            'The Great Gatsby' => 'covers/the great gatsby.jpeg',
            'To Kill a Mockingbird' => 'covers/to kill a mockingbird.jpeg',
            'Pride and Prejudice' => 'covers/prideandprjudice.jpeg',
            'The 7 Habits of Highly Effective People' => 'covers/the 7 habits of highly effevctive people.jpeg',
        ];

        foreach ($coverMappings as $bookTitle => $coverPath) {
            $book = Book::where('title', $bookTitle)->first();
            
            if ($book) {
                $book->cover_image = 'storage/' . $coverPath;
                $book->save();
                $this->info("Updated: {$book->title} -> {$coverPath}");
            } else {
                $this->warn("Book not found: {$bookTitle}");
            }
        }

        $this->info('Book covers updated successfully!');
    }
}
