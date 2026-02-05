<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of borrowings.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with('user', 'book');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new borrowing.
     */
    public function create()
    {
        $books = Book::where('is_active', true)->get();
        $users = User::where('is_active', true)->orWhere('role', 'user')->get();

        return view('admin.borrowings.create', compact('books', 'users'));
    }

    /**
     * Store a newly created borrowing in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_date' => 'required|date',
            'due_date' => 'required|date|after:borrowed_date',
            'notes' => 'nullable|string|max:500',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->available_copies <= 0) {
            return back()->withErrors(['book_id' => 'Buku tidak tersedia.'])->withInput();
        }

        $borrowing = Borrowing::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'borrowed_at' => $validated['borrowed_date'],
            'due_date' => $validated['due_date'],
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        $book->decrement('available_copies');

        return redirect()->route('admin.borrowings.index')
                       ->with('success', 'Peminjaman berhasil dibuat.');
    }

    /**
     * Display the specified borrowing.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load('user', 'book');

        return view('admin.borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified borrowing.
     */
    public function edit(Borrowing $borrowing)
    {
        $borrowing->load('user', 'book');
        $books = Book::where('is_active', true)->get();
        $users = User::where('is_active', true)->orWhere('role', 'user')->get();

        return view('admin.borrowings.edit', compact('borrowing', 'books', 'users'));
    }

    /**
     * Update the specified borrowing in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'borrowed_date' => 'required|date',
            'due_date' => 'required|date|after:borrowed_date',
            'status' => 'required|in:active,returned,overdue',
            'notes' => 'nullable|string|max:500',
        ]);

        $borrowing->update($validated);

        return redirect()->route('admin.borrowings.index')
                       ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified borrowing from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        if ($borrowing->status != 'returned') {
            $borrowing->book->increment('available_copies');
        }

        $borrowing->delete();

        return redirect()->route('admin.borrowings.index')
                       ->with('success', 'Peminjaman berhasil dihapus.');
    }

    /**
     * Approve return of a borrowing.
     */
    public function approveReturn(Borrowing $borrowing)
    {
        $borrowing->update([
            'status' => 'returned',
            'returned_date' => now(),
        ]);

        $borrowing->book->increment('available_copies');

        return redirect()->route('admin.borrowings.index')
                       ->with('success', 'Pengembalian buku berhasil disetujui.');
    }

    /**
     * Approve pending borrowing request.
     */
    public function approvePending(Borrowing $borrowing)
    {
        // Accept both 'pending' and 'pending_petugas' statuses
        if (!in_array($borrowing->status, ['pending', 'pending_petugas'])) {
            return back()->with('error', 'Hanya peminjaman dengan status "pending" atau "menunggu admin" yang dapat disetujui.');
        }

        // Check if book is still available
        if (!$borrowing->book->isAvailable()) {
            return back()->with('error', 'Buku tidak tersedia lagi untuk dipinjam.');
        }

        // Update status to active
        $borrowing->update([
            'status' => 'active',
            'borrowed_at' => $borrowing->borrow_date ?? now(),
        ]);

        // Decrease book stock
        $borrowing->book->decrement('available_copies');

        // Create notification for user
        \App\Models\Notification::create([
            'user_id' => $borrowing->user_id,
            'type' => 'borrowing_approved_admin',
            'title' => 'Peminjaman Disetujui Admin',
            'message' => "Permintaan peminjaman buku \"{$borrowing->book->title}\" telah disetujui oleh admin. Silakan ambil buku di perpustakaan.",
            'related_model' => 'Borrowing',
            'related_id' => $borrowing->id,
        ]);

        return back()->with('success', 'Peminjaman berhasil disetujui. User dapat mengambil buku.');
    }

    /**
     * Reject pending borrowing request.
     */
    public function rejectPending(Borrowing $borrowing)
    {
        // Accept both 'pending' and 'pending_petugas' statuses
        if (!in_array($borrowing->status, ['pending', 'pending_petugas'])) {
            return back()->with('error', 'Hanya peminjaman dengan status "pending" atau "menunggu admin" yang dapat ditolak.');
        }

        $bookTitle = $borrowing->book->title;
        $borrowing->delete();

        // Create notification for user
        \App\Models\Notification::create([
            'user_id' => $borrowing->user_id,
            'type' => 'borrowing_rejected_admin',
            'title' => 'Peminjaman Ditolak Admin',
            'message' => "Permintaan peminjaman buku \"{$bookTitle}\" telah ditolak oleh admin.",
            'related_model' => 'Borrowing',
            'related_id' => $borrowing->id,
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Generate PDF proof of borrowing.
     */
    public function printProof(Borrowing $borrowing)
    {
        $borrowing->load('user', 'book');

        $pdf = \PDF::loadView('admin.borrowings.proof', compact('borrowing'));
        $filename = "bukti-peminjaman-{$borrowing->id}-{$borrowing->user->name}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Export borrowings to CSV.
     */
    public function exportCSV(Request $request)
    {
        $query = Borrowing::with('user', 'book');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->get();

        $filename = "peminjaman-" . now()->format('Y-m-d-His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($borrowings) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'User', 'Email', 'Buku', 'Tanggal Pinjam', 'Tanggal Kembali',
                'Durasi (hari)', 'Status', 'Catatan'
            ]);

            // Data rows
            foreach ($borrowings as $borrowing) {
                fputcsv($file, [
                    $borrowing->id,
                    $borrowing->user->name,
                    $borrowing->user->email,
                    $borrowing->book->title,
                    optional($borrowing->borrowed_at)->format('d/m/Y'),
                    optional($borrowing->due_date)->format('d/m/Y'),
                    $borrowing->duration_days ?? '-',
                    ucfirst($borrowing->status),
                    $borrowing->notes ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
