<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:petugas']);
    }

    /**
     * Show the petugas dashboard.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'pending' => Borrowing::where('status', 'pending')->count(),
            'active' => Borrowing::where('status', 'active')->count(),
            'returned' => Borrowing::where('status', 'returned')->count(),
            'overdue' => Borrowing::where('status', 'overdue')->count(),
        ];

        // Get pending approvals for this petugas
        $pending_approvals = Borrowing::with('user', 'book')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        // Get recent borrowings
        $recent_borrowings = Borrowing::with('user', 'book')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('petugas.dashboard', compact('stats', 'pending_approvals', 'recent_borrowings'));
    }
}
