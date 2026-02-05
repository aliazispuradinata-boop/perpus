<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;

class FetchBookCovers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:book-covers {--limit=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch book covers for books missing cover_image using OpenLibrary or Google Books';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');
        $books = Book::whereNull('cover_image')->limit($limit)->get();

        if ($books->isEmpty()) {
            $this->info('No books without cover_image found.');
            return 0;
        }

        foreach ($books as $book) {
            $this->info("Processing: {$book->id} - {$book->title}");

            // Try OpenLibrary by ISBN
            $saved = false;
            if ($book->isbn) {
                $olUrl = "https://covers.openlibrary.org/b/isbn/{$book->isbn}-L.jpg";
                if ($this->urlExists($olUrl)) {
                    $saved = $this->downloadAndSave($olUrl, $book);
                }
            }

            // If not saved, try Google Books API by ISBN then by title+author
            if (!$saved) {
                $query = $book->isbn ? 'isbn:' . urlencode($book->isbn) : 'intitle:' . urlencode($book->title);
                if ($book->author) {
                    $query .= '+inauthor:' . urlencode($book->author);
                }

                $apiUrl = 'https://www.googleapis.com/books/v1/volumes?q=' . $query;
                if (env('GOOGLE_BOOKS_API_KEY')) {
                    $apiUrl .= '&key=' . env('GOOGLE_BOOKS_API_KEY');
                }

                $json = @file_get_contents($apiUrl);
                if ($json) {
                    $data = json_decode($json, true);
                    if (!empty($data['items'][0]['volumeInfo']['imageLinks'])) {
                        $links = $data['items'][0]['volumeInfo']['imageLinks'];
                        $imgUrl = $links['thumbnail'] ?? $links['smallThumbnail'] ?? null;
                        if ($imgUrl) {
                            // Some thumbnails are served via HTTP; ensure https
                            $imgUrl = preg_replace('/^http:/i', 'https:', $imgUrl);
                            $saved = $this->downloadAndSave($imgUrl, $book);
                        }
                    }
                }
            }

            if ($saved) {
                $this->info("Saved cover for book ID {$book->id}");
            } else {
                $this->warn("No cover found for book ID {$book->id}");
            }
        }

        $this->info('Done.');
        return 0;
    }

    protected function urlExists(string $url): bool
    {
        $headers = @get_headers($url);
        return $headers && strpos($headers[0], '200') !== false;
    }

    protected function downloadAndSave(string $url, Book $book): bool
    {
        try {
            $contents = @file_get_contents($url);
            if ($contents === false) return false;

            $ext = 'jpg';
            // try to detect extension from headers
            $finfo = finfo_open();
            $mime = finfo_buffer($finfo, $contents, FILEINFO_MIME_TYPE);
            finfo_close($finfo);
            if ($mime === 'image/png') $ext = 'png';

            $filename = 'covers/' . md5($book->id . $book->title . time()) . '.' . $ext;
            Storage::disk('public')->put($filename, $contents);
            $book->cover_image = $filename;
            $book->save();
            return true;
        } catch (\Exception $e) {
            $this->error('Error downloading: ' . $e->getMessage());
            return false;
        }
    }
}
