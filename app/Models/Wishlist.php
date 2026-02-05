<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'priority',
        'added_at'
    ];

    protected $casts = [
        'added_at' => 'datetime',
        'priority' => 'integer',
    ];

    /**
     * Get the user who added to wishlist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wishlisted book.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
