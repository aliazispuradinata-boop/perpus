<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Services\CoverGeneratorService;
use Illuminate\Console\Command;

class AssignBookCovers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:assign-covers {--force : Force re-assign untuk buku yang sudah punya cover}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Assign cover buku dari Google Books API berdasarkan ISBN';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        
        // Query buku
        $query = Book::query();
        
        if (!$force) {
            // Hanya buku tanpa cover
            $query->where(function($q) {
                $q->whereNull('cover_image')
                  ->orWhere('cover_image', '')
                  ->orWhereRaw("cover_image LIKE 'https://via.placeholder%'");
            });
        }

        $books = $query->get();
        $total = $books->count();

        if ($total === 0) {
            $this->info('✓ Tidak ada buku yang perlu di-update!');
            return 0;
        }

        $this->info("📚 Assigning cover untuk $total buku...\n");

        $progress = $this->output->createProgressBar($total);
        $progress->start();

        $success = 0;
        $failed = 0;
        $failedBooks = [];

        foreach ($books as $book) {
            try {
                // Prioritas: ISBN → Title → Author
                $imageUrl = null;
                
                if ($book->isbn) {
                    $imageUrl = CoverGeneratorService::generateCoverFromISBN(
                        $book->isbn,
                        $book->title,
                        $book->author
                    );
                } else {
                    $imageUrl = CoverGeneratorService::generateCoverFromTitle(
                        $book->title,
                        $book->author
                    );
                }

                // Save cover ke storage
                $coverPath = CoverGeneratorService::saveCoverFromUrl(
                    $imageUrl,
                    $book->title
                );

                if ($coverPath) {
                    $book->update(['cover_image' => $coverPath]);
                    $success++;
                } else {
                    $failed++;
                    $failedBooks[] = $book->title;
                }
            } catch (\Exception $e) {
                $failed++;
                $failedBooks[] = $book->title . ' (' . $e->getMessage() . ')';
                \Log::error("Failed to assign cover for book: {$book->title}", [
                    'error' => $e->getMessage()
                ]);
            }

            $progress->advance();
        }

        $progress->finish();

        $this->newLine(2);
        $this->info("✓ Selesai!");
        $this->line("  📗 Berhasil: $success");
        $this->line("  ❌ Gagal: $failed");
        
        if (!empty($failedBooks)) {
            $this->warn("\n⚠️  Buku yang gagal:");
            foreach ($failedBooks as $book) {
                $this->warn("  - $book");
            }
        }

        return 0;
    }
}
