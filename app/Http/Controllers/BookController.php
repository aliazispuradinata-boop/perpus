<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Show books catalog.
     */
    public function index(Request $request)
    {
        $query = Book::where('is_active', true);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $category = Category::where('slug', $request->category)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'author':
                $query->orderBy('author', 'asc');
                break;
            default:
                $query->latest();
        }

        $books = $query->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('display_order')->get();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Show book details.
     */
    public function show($slug)
    {
        $book = Book::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $reviews = $book->reviews()
            ->where('is_published', true)
            ->with('user')
            ->latest()
            ->get();

        $user_review = null;
        $is_in_wishlist = false;
        $can_review = false;

        if (auth()->check()) {
            $user_review = auth()->user()->reviews()->where('book_id', $book->id)->first();
            $is_in_wishlist = Wishlist::where('user_id', auth()->id())
                ->where('book_id', $book->id)
                ->exists();
            $can_review = auth()->user()->borrowings()
                ->where('book_id', $book->id)
                ->where('status', 'returned')
                ->exists();
        }

        $related_books = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('books.show', compact('book', 'reviews', 'user_review', 'is_in_wishlist', 'can_review', 'related_books'));
    }

    /**
     * Add book to wishlist.
     */
    public function addToWishlist(Request $request, Book $book)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu'], 401);
        }

        $exists = Wishlist::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Buku sudah ada di wishlist'], 400);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'added_at' => now(),
        ]);

        return response()->json(['message' => 'Buku berhasil ditambahkan ke wishlist']);
    }

    /**
     * Remove book from wishlist.
     */
    public function removeFromWishlist(Request $request, Book $book)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->delete();

        return response()->json(['message' => 'Buku dihapus dari wishlist']);
    }

    /**
     * Show user's wishlist / favorites.
     */
    public function wishlist(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $query = auth()->user()->wishlists()->with('book');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'title':
                $query->orderBy('book.title', 'asc');
                break;
            case 'author':
                $query->orderBy('book.author', 'asc');
                break;
            default:
                $query->orderBy('wishlists.created_at', 'desc');
        }

        $wishlists = $query->paginate(12);
        $wishlist_books = $wishlists->pluck('book');

        return view('books.wishlist', compact('wishlists', 'wishlist_books'));
    }

    /**
     * Bulk borrow from wishlist
     */
    public function borrowFromWishlist(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $validated = $request->validate([
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id'
        ]);

        $successCount = 0;
        $failedCount = 0;

        foreach ($validated['book_ids'] as $bookId) {
            $book = Book::find($bookId);
            
            if ($book && $book->available_copies > 0) {
                // Create borrowing
                \App\Models\Borrowing::create([
                    'user_id' => auth()->id(),
                    'book_id' => $bookId,
                    'borrowed_at' => now(),
                    'due_date' => now()->addDays(7),
                    'status' => 'pending',
                ]);
                
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        $message = "Berhasil meminjam $successCount buku";
        if ($failedCount > 0) {
            $message .= ". $failedCount buku tidak tersedia";
        }

        return back()->with('success', $message);
    }

    /**
     * Store review.
     */
    public function storeReview(Request $request, Book $book)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10',
        ]);

        $review = Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'book_id' => $book->id,
            ],
            [
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'content' => $validated['content'],
                'is_verified_purchase' => auth()->user()->borrowings()
                    ->where('book_id', $book->id)
                    ->where('status', 'returned')
                    ->exists(),
                // Require admin moderation before publishing
                'is_published' => false,
            ]
        );

        // Update book rating
        $book->updateRating();

        // Notify admins of new review
        try {
            \App\Models\Notification::create([
                'user_id' => auth()->id(),
                'review_id' => $review->id,
                'type' => 'new_review',
                'title' => 'Ulasan Baru',
                'message' => "{$review->user->name} memberikan ulasan untuk buku \"{$book->title}\".",
                'is_read' => false,
            ]);
            
            // Send email to admin with review details
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                try {
                    \Mail::send('emails.new_review_notification', [
                        'review' => $review,
                        'book' => $book,
                        'user' => auth()->user(),
                    ], function ($message) use ($admin) {
                        $message->to($admin->email)
                            ->subject('[RetroLib] Ulasan Baru: ' . $book->title);
                    });
                } catch (\Exception $e) {
                    // Silently fail email sending
                }
            }
        } catch (\Exception $e) {
            // Ignore notification failure
        }

        return redirect()->route('books.show', $book->slug)
            ->with('success', 'Review berhasil dikirim dan menunggu moderasi admin.');
    }
}
