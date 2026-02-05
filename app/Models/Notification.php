<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * Allowed notification types (must match DB enum).
     */
    protected static array $allowedTypes = [
        'due_soon', 'overdue', 'returned', 'available', 'new_book', 'system'
    ];

    protected $fillable = [
        'user_id',
        'borrowing_id',
        'type',
        'title',
        'message',
        'is_read',
        'read_at',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
    ];

    /**
     * Get the user who received the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related borrowing.
     */
    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(Borrowing::class);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Ensure type is one of allowed values before creating to avoid DB enum errors.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!in_array($model->type, self::$allowedTypes)) {
                $model->type = 'system';
            }
        });
    }
}
