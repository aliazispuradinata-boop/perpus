<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:petugas']);
    }

    /**
     * Display a listing of books (petugas view-only).
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

        return view('petugas.books.index', compact('books', 'categories'));
    }

    /**
     * Show the specified book.
     */
    public function show(Book $book)
    {
        $book->load('category', 'borrowings');
        return view('petugas.books.show', compact('book'));
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

            fputcsv($file, [
                'ID', 'Judul', 'Penulis', 'Penerbit', 'Kategori', 'ISBN', 
                'Total Stok', 'Stok Tersedia', 'Status', 'Featured'
            ]);

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
}
