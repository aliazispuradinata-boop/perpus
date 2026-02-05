<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // show pending reviews for moderation
        $reviews = Review::with('user', 'book')
            ->where('is_published', false)
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->is_published = true;
        $review->save();

        // update book rating after publish
        $review->book->updateRating();

        return redirect()->back()->with('success', 'Ulasan disetujui.');
    }

    public function reject(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Ulasan ditolak dan dihapus.');
    }

    /**
     * Bulk approve reviews.
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu ulasan.');
        }

        $reviews = Review::whereIn('id', $ids)->get();
        foreach ($reviews as $review) {
            $review->is_published = true;
            $review->save();
            $review->book->updateRating();
        }

        return redirect()->back()->with('success', 'Ulasan terpilih berhasil disetujui.');
    }

    /**
     * Bulk reject reviews.
     */
    public function bulkReject(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu ulasan.');
        }

        Review::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Ulasan terpilih berhasil ditolak dan dihapus.');
    }

    /**
     * Export reviews to CSV.
     */
    public function exportCSV()
    {
        $reviews = Review::with('user', 'book')
            ->where('is_published', false)
            ->latest()
            ->get();

        $filename = "ulasan-pending-" . now()->format('Y-m-d-His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($reviews) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID', 'User', 'Email', 'Buku', 'Rating', 'Isi Ulasan', 'Tanggal', 'Status'
            ]);

            // Data rows
            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->user->name,
                    $review->user->email,
                    $review->book->title,
                    $review->rating,
                    Str::limit($review->content, 200),
                    $review->created_at->format('d/m/Y H:i'),
                    $review->is_published ? 'Published' : 'Pending'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
