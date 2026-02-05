<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status',
        'renewal_count',
        'last_renewal_date',
        'notes',
        'qr_code',
        'duration_days',
        'fine_notes',
        'borrow_date',
        'confirmed_at',
        'confirmed_by_petugas_id',
        'verified_by_petugas_id'
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
        'last_renewal_date' => 'datetime',
        'borrow_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'renewal_count' => 'integer',
    ];

    /**
     * Get the user who borrowed the book.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowed book.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if borrowing is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && now() > $this->due_date;
    }

    /**
     * Check if borrowing can be renewed.
     */
    public function canRenew(): bool
    {
        return $this->status === 'active' && $this->renewal_count < 3;
    }

    /**
     * Renew the borrowing for another 14 days.
     */
    public function renew(): void
    {
        if ($this->canRenew()) {
            $this->due_date = now()->addDays(14);
            $this->renewal_count++;
            $this->last_renewal_date = now();
            $this->save();
        }
    }

    /**
     * Mark borrowing as returned.
     */
    public function markAsReturned(): void
    {
        $this->returned_at = now();
        $this->status = 'returned';
        $this->save();

        // Increase available copies
        $this->book->increment('available_copies');
    }
}
