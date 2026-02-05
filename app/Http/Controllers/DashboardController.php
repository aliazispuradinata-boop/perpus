<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isPetugas()) {
            return $this->petuguasDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    /**
     * Admin dashboard.
     */
    private function adminDashboard()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_users' => \App\Models\User::count(),
            'active_borrowings' => Borrowing::where('status', 'active')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'overdue')->count(),
            'total_reviews' => Review::count(),
        ];

        $recent_borrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->limit(10)
            ->get();

        $featured_books = Book::where('is_featured', true)
            ->where('is_active', true)
            ->limit(5)
            ->get();

        // Users list for admin table (paginated)
        $users = \App\Models\User::orderBy('created_at', 'desc')->paginate(15);

        return view('dashboard.admin', compact('stats', 'recent_borrowings', 'featured_books', 'users'));
    }

    /**
     * Petugas dashboard.
     */
    private function petuguasDashboard()
    {
        $user = auth()->user();

        $stats = [
            'active_borrowings' => $user->borrowings()->where('status', 'active')->count(),
            'overdue_borrowings' => $user->borrowings()->where('status', 'overdue')->count(),
            'total_returned' => $user->borrowings()->where('status', 'returned')->count(),
            'wishlist_count' => $user->wishlists()->count(),
        ];

        $active_borrowings = $user->borrowings()
            ->with('book')
            ->where('status', '!=', 'returned')
            ->latest()
            ->get();

        $wishlist_books = $user->wishlists()
            ->with('book')
            ->latest()
            ->limit(5)
            ->get();

        $featured_books = Book::where('is_featured', true)
            ->where('is_active', true)
            ->limit(5)
            ->get();

        return view('dashboard.petugas', compact('stats', 'active_borrowings', 'wishlist_books', 'featured_books'));
    }

    /**
     * User dashboard.
     */
    private function userDashboard()
    {
        $featured_books = Book::where('is_featured', true)
            ->where('is_active', true)
            ->limit(8)
            ->get();

        $trending_books = Book::where('is_active', true)
            ->orderBy('rating', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard.user', compact('featured_books', 'trending_books'));
    }
}
