<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'author',
        'isbn',
        'publisher',
        'published_year',
        'pages',
        'language',
        'cover_image',
        'total_copies',
        'available_copies',
        'rating',
        'review_count',
        'content_preview',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'published_year' => 'integer',
        'pages' => 'integer',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
        'rating' => 'float',
        'review_count' => 'integer',
    ];

    /**
     * Accessor to get a usable cover URL for views.
     * - Returns full URL if stored value is already a URL.
     * - Returns asset('storage/...') when stored path is relative to storage.
     * - Returns placeholder image when no cover is set.
     */
    public function getCoverUrlAttribute()
    {
        if (!$this->cover_image) {
            return asset('images/placeholder-book.png');
        }

        $path = $this->cover_image;

        // If already an absolute URL
        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        // If path already includes storage/ prefix
        if (strpos($path, 'storage/') === 0) {
            return asset($path);
        }

        // Otherwise assume it's a storage disk path like 'covers/..'
        return asset('storage/' . ltrim($path, '/'));
    }

    /**
     * Get the category of this book.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all borrowings for this book.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Get all reviews for this book.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all wishlist entries for this book.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Calculate average rating from reviews.
     */
    public function updateRating(): void
    {
        $reviews = $this->reviews()->where('is_published', true)->get();
        if ($reviews->count() > 0) {
            $this->rating = $reviews->avg('rating');
            $this->review_count = $reviews->count();
            $this->save();
        }
    }

    /**
     * Check if book is available.
     */
    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->is_active;
    }
}
