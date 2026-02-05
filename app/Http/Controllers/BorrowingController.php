<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class BorrowingController extends Controller
{
    /**
     * Show borrowing history.
     */
    public function history(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->borrowings()->with('book');
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $borrowings = $query->latest()->paginate(15);

        return view('borrowings.history', compact('borrowings'));
    }

    /**
     * Borrow a book.
     */
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Validate input
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'duration_days' => 'required|integer|min:1|max:30',
            'borrow_date' => 'required|date|after_or_equal:today',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        // Check if book is available
        if (!$book->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak tersedia untuk dipinjam.'
            ], 400);
        }

        // Check if user already has this book
        $already_borrowed = Borrowing::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($already_borrowed) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah meminjam buku ini.'
            ], 400);
        }

        $borrow_date = Carbon::parse($validated['borrow_date']);
        $due_date = $borrow_date->copy()->addDays($validated['duration_days']);

        // Generate QR Code
        $qr_code_id = Str::uuid();
        $qr_code_path = 'qrcodes/' . $qr_code_id . '.png';
        
        // Store QR code as data
        $qr_data = json_encode([
            'id' => $qr_code_id,
            'book_id' => $book->id,
            'user_id' => auth()->id(),
            'timestamp' => now()->timestamp
        ]);

        try {
            QrCode::format('png')
                ->size(200)
                ->generate($qr_data, storage_path('app/public/' . $qr_code_path));
        } catch (\Exception $e) {
            // If QR code generation fails, we'll still create the borrowing record
            $qr_code_path = null;
        }

        // Create borrowing record with status pending
        $borrowing = Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'borrowed_at' => $borrow_date,
            'due_date' => $due_date,
            'status' => 'pending',  // Pending for admin confirmation
            'duration_days' => $validated['duration_days'],
            'qr_code' => $qr_code_path,
        ]);

        // Create notification for admin
        Notification::create([
            'user_id' => auth()->id(),
            'borrowing_id' => $borrowing->id,
            'type' => 'pending_confirmation',
            'title' => 'Peminjaman Menunggu Konfirmasi',
            'message' => "Anda mengajukan peminjaman buku \"{$book->title}\" selama {$validated['duration_days']} hari hingga " . $due_date->format('d M Y') . ". Menunggu konfirmasi petugas.",
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permintaan peminjaman berhasil dikirim! Menunggu konfirmasi petugas.',
            'borrowing_id' => $borrowing->id,
            'qr_code' => $qr_code_path ? asset('storage/' . $qr_code_path) : null,
            'redirect_url' => route('borrowings.proof', $borrowing->id)
        ]);
    }

    /**
     * Show printable receipt for a borrowing (user or admin).
     */
    public function receipt(Borrowing $borrowing)
    {
        if (auth()->id() !== $borrowing->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $borrowing->load('user', 'book');

        return view('borrowings.receipt', compact('borrowing'));
    }

    /**
     * Return a book.
     */
    public function return(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $book = $borrowing->book;
        $borrowing->markAsReturned();

        Notification::create([
            'user_id' => $borrowing->user_id,
            'borrowing_id' => $borrowing->id,
            'type' => 'returned',
            'title' => 'Pengembalian Buku',
            'message' => "Buku \"{$book->title}\" telah berhasil dikembalikan.",
            'is_read' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Buku berhasil dikembalikan!');
    }

    /**
     * Renew borrowing.
     */
    public function renew(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$borrowing->canRenew()) {
            return redirect()->back()
                ->with('error', 'Perpanjangan tidak dapat dilakukan lagi.');
        }

        $borrowing->renew();

        Notification::create([
            'user_id' => $borrowing->user_id,
            'borrowing_id' => $borrowing->id,
            'type' => 'due_soon',
            'title' => 'Perpanjangan Peminjaman',
            'message' => "Peminjaman buku \"{$borrowing->book->title}\" diperpanjang hingga " . $borrowing->due_date->format('d M Y'),
            'is_read' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Peminjaman berhasil diperpanjang!');
    }

    /**
     * Get overdue borrowings and create notifications.
     */
    public function checkOverdue()
    {
        $borrowings = Borrowing::where('status', 'active')
            ->where('due_date', '<', now())
            ->get();

        foreach ($borrowings as $borrowing) {
            if ($borrowing->status !== 'overdue') {
                $borrowing->update(['status' => 'overdue']);

                Notification::create([
                    'user_id' => $borrowing->user_id,
                    'borrowing_id' => $borrowing->id,
                    'type' => 'overdue',
                    'title' => 'Buku Terlambat Dikembalikan',
                    'message' => "Buku \"{$borrowing->book->title}\" telah terlambat dikembalikan. Mohon segera kembalikan.",
                    'is_read' => false,
                ]);
            }
        }

        return response()->json(['message' => 'Overdue check completed']);
    }

    /**
     * Show proof of borrowing for user.
     */
    public function proof(Borrowing $borrowing)
    {
        // Only user who borrowed or admin can view
        if (auth()->id() !== $borrowing->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $borrowing->load('user', 'book', 'book.reviews');
        
        // Get reviews for this book by the current user (if any)
        $userReview = null;
        if (auth()->check()) {
            $userReview = $borrowing->book->reviews()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('borrowings.proof', compact('borrowing', 'userReview'));
    }
}
