<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Notification;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:petugas']);
    }

    /**
     * Display a listing of all borrowings for verification.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with('user', 'book');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by user name or book title
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('petugas.borrowings.index', compact('borrowings'));
    }

    /**
     * Show borrowing details.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load('user', 'book');
        return view('petugas.borrowings.show', compact('borrowing'));
    }

    /**
     * Approve borrowing (Petugas approves, then goes to Admin).
     */
    public function approvePending(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman status pending yang dapat disetujui.');
        }

        $borrowing->update([
            'status' => 'pending_petugas',
        ]);

        // Create notification
        Notification::create([
            'user_id' => $borrowing->user_id,
            'type' => 'borrowing_approved_petugas',
            'title' => 'Peminjaman Disetujui Petugas',
            'message' => "Peminjaman buku '{$borrowing->book->title}' telah disetujui oleh petugas. Menunggu persetujuan admin.",
            'related_model' => 'Borrowing',
            'related_id' => $borrowing->id,
        ]);

        return back()->with('success', 'Peminjaman berhasil disetujui. Diteruskan ke admin untuk persetujuan akhir.');
    }

    /**
     * Reject borrowing (Petugas rejects).
     */
    public function rejectPending(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman status pending yang dapat ditolak.');
        }

        $borrowing->delete();

        // Create notification
        Notification::create([
            'user_id' => $borrowing->user_id,
            'type' => 'borrowing_rejected',
            'title' => 'Peminjaman Ditolak',
            'message' => "Peminjaman buku '{$borrowing->book->title}' telah ditolak oleh petugas.",
            'related_model' => 'Borrowing',
            'related_id' => $borrowing->id,
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Confirm/Verify borrowing when user picks up the book.
     */
    public function confirm(Borrowing $borrowing)
    {
        // Only active borrowings can be confirmed (user has approval from admin)
        if ($borrowing->status !== 'active') {
            return back()->with('error', 'Hanya peminjaman yang telah disetujui yang dapat dikonfirmasi.');
        }

        $borrowing->update([
            'confirmed_at' => now(),
            'confirmed_by_petugas_id' => auth()->id(),
        ]);

        // Create notification
        Notification::create([
            'user_id' => $borrowing->user_id,
            'type' => 'borrowing_confirmed',
            'title' => 'Peminjaman Dikonfirmasi',
            'message' => "Peminjaman buku '{$borrowing->book->title}' telah dikonfirmasi oleh petugas.",
            'related_model' => 'Borrowing',
            'related_id' => $borrowing->id,
        ]);

        return back()->with('success', 'Peminjaman berhasil dikonfirmasi.');
    }

    /**
     * Verify book return.
     */
    public function verifyReturn(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'active') {
            return back()->with('error', 'Hanya peminjaman yang aktif yang dapat dikonfirmasi pengembalian.');
        }

        $borrowing->update([
            'status' => 'returned',
            'returned_at' => now(),
            'verified_by_petugas_id' => auth()->id(),
        ]);

        // Increment book stock
        $borrowing->book->increment('available_copies');

        // Create notification
        Notification::create([
            'user_id' => $borrowing->user_id,
            'type' => 'book_returned',
            'title' => 'Buku Dikembalikan',
            'message' => "Pengembalian buku '{$borrowing->book->title}' telah dikonfirmasi.",
            'related_model' => 'Borrowing',
            'related_id' => $borrowing->id,
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dikonfirmasi.');
    }

    /**
     * View borrowing history/status.
     */
    public function history(Request $request)
    {
        $query = Borrowing::with('user', 'book');

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get list of users for filter dropdown
        $users = \App\Models\User::where('role', 'user')->orderBy('name')->get();

        return view('petugas.borrowings.history', compact('borrowings', 'users'));
    }

    /**
     * Export borrowing data to CSV.
     */
    public function export(Request $request)
    {
        $query = Borrowing::with('user', 'book');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderBy('created_at', 'desc')->get();

        $filename = 'peminjaman_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($borrowings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

            // Header row
            fputcsv($file, [
                'ID',
                'User',
                'Email',
                'Buku',
                'Tanggal Pinjam',
                'Tanggal Kembali',
                'Durasi (Hari)',
                'Status',
                'Denda',
            ], ';');

            // Data rows
            foreach ($borrowings as $borrowing) {
                fputcsv($file, [
                    $borrowing->id,
                    $borrowing->user->name,
                    $borrowing->user->email,
                    $borrowing->book->title,
                    $borrowing->borrow_date->format('d-m-Y'),
                    $borrowing->due_date->format('d-m-Y'),
                    $borrowing->duration_days,
                    $borrowing->status,
                    $borrowing->fine_notes ?? '-',
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
