<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HelperController extends Controller
{
    public function updateBookCovers()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response('Unauthorized', 403);
        }

        $coverMappings = [
            'The Great Gatsby' => 'storage/covers/the great gatsby.jpeg',
            'To Kill a Mockingbird' => 'storage/covers/to kill a mockingbird.jpeg',
            'Pride and Prejudice' => 'storage/covers/prideandprjudice.jpeg',
            'The 7 Habits of Highly Effective People' => 'storage/covers/the 7 habits of highly effevctive people.jpeg',
        ];

        $updated = [];
        foreach ($coverMappings as $bookTitle => $coverPath) {
            $book = Book::where('title', $bookTitle)->first();
            
            if ($book) {
                $book->cover_image = $coverPath;
                $book->save();
                $updated[] = "✓ {$book->title}";
            } else {
                $updated[] = "✗ Not found: {$bookTitle}";
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Book covers updated',
            'updated' => $updated
        ]);
    }
}
