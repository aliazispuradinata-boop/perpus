<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Services\CoverGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Show all books for admin.
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $books = $query->paginate(15);
        $categories = Category::all();

        return view('admin.books.index', compact('books', 'categories'));
    }

    /**
     * Show create book form.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store new book.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255|unique:books',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|unique:books',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'total_copies' => 'required|integer|min:1',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'cover_image' => 'nullable|image|max:2048',
            'cover_path' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['available_copies'] = $validated['total_copies'];
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active') ? true : true;

        // Handle generated cover path
        if (!empty($validated['cover_path'])) {
            $validated['cover_image'] = $validated['cover_path'];
            unset($validated['cover_path']);
        }
        // Handle cover image upload
        elseif ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image'] = $path;
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Show edit book form.
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Update book.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255|unique:books,title,' . $book->id,
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'total_copies' => 'required|integer|min:1',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'cover_image' => 'nullable|image|max:2048',
            'cover_path' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active') ? true : true;

        // Update available copies based on total copies change
        $diff = $validated['total_copies'] - $book->total_copies;
        if ($diff > 0) {
            $validated['available_copies'] = $book->available_copies + $diff;
        } elseif ($diff < 0 && ($book->available_copies + $diff) >= 0) {
            $validated['available_copies'] = $book->available_copies + $diff;
        }

        // Handle generated cover path
        if (!empty($validated['cover_path'])) {
            if ($book->cover_image && \Storage::disk('public')->exists($book->cover_image)) {
                \Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $validated['cover_path'];
            unset($validated['cover_path']);
        }
        // Handle cover image upload and remove old if exists
        elseif ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            if ($book->cover_image && \Storage::disk('public')->exists($book->cover_image)) {
                \Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $path;
        }

        unset($validated['cover_path']);
        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diperbarui!');
    }

    /**
     * Delete book.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil dihapus!');
    }

    /**
     * Export books to CSV.
     */
    public function exportCSV(Request $request)
    {
        $query = Book::with('category');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $books = $query->get();

        $filename = "daftar-buku-" . now()->format('Y-m-d-His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($books) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'Judul', 'Penulis', 'Penerbit', 'Kategori', 'ISBN', 
                'Total Stok', 'Stok Tersedia', 'Status', 'Featured'
            ]);

            // Data rows
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->id,
                    $book->title,
                    $book->author,
                    $book->publisher ?? '-',
                    $book->category->name ?? '-',
                    $book->isbn ?? '-',
                    $book->total_copies,
                    $book->available_copies,
                    $book->is_active ? 'Aktif' : 'Nonaktif',
                    $book->is_featured ? 'Ya' : 'Tidak'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate cover image dari title buku
     */
    public function generateCover(Request $request)
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
        ]);

        try {
            // Generate cover image URL
            $imageUrl = CoverGeneratorService::generateCoverFromTitle(
                $validated['title'],
                $validated['author'] ?? null
            );

            return response()->json([
                'success' => true,
                'image_url' => $imageUrl,
                'message' => 'Cover berhasil digenerate!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate cover: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Save generated cover ke storage
     */
    public function saveCover(Request $request)
    {
        $validated = $request->validate([
            'image_url' => 'required|url',
            'title' => 'required|string|max:255',
        ]);

        try {
            $path = CoverGeneratorService::saveCoverFromUrl(
                $validated['image_url'],
                $validated['title']
            );

            if (!$path) {
                throw new \Exception('Gagal menyimpan gambar');
            }

            return response()->json([
                'success' => true,
                'path' => $path,
                'message' => 'Cover berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan cover: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Batch assign cover untuk semua buku yang belum memiliki cover
     */
    public function batchAssignCovers(Request $request)
    {
        try {
            // Query buku tanpa cover atau dengan placeholder
            $books = Book::where(function($q) {
                $q->whereNull('cover_image')
                  ->orWhere('cover_image', '')
                  ->orWhereRaw("cover_image LIKE 'https://via.placeholder%'");
            })->get();

            if ($books->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Semua buku sudah memiliki cover!',
                    'total' => 0,
                    'success_count' => 0,
                    'failed_count' => 0
                ]);
            }

            $total = $books->count();
            $success = 0;
            $failed = 0;
            $failedBooks = [];

            foreach ($books as $book) {
                try {
                    // Prioritas: ISBN → Title
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
                    $failedBooks[] = $book->title . ' (' . substr($e->getMessage(), 0, 50) . ')';
                    \Log::error("Failed to assign cover for book: {$book->title}", [
                        'error' => $e->getMessage()
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Batch assignment selesai!",
                'total' => $total,
                'success_count' => $success,
                'failed_count' => $failed,
                'failed_books' => $failedBooks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal batch assign: ' . $e->getMessage()
            ], 400);
        }
    }
}

