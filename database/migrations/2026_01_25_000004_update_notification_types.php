<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update notification type enum untuk menambah tipe notifikasi petugas dan admin
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM(
            'due_soon',
            'overdue',
            'returned',
            'available',
            'new_book',
            'system',
            'borrowing_approved_petugas',
            'borrowing_approved_admin',
            'borrowing_rejected',
            'borrowing_rejected_admin',
            'borrowing_confirmed',
            'book_returned'
        ) NOT NULL DEFAULT 'system'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM(
            'due_soon',
            'overdue',
            'returned',
            'available',
            'new_book',
            'system'
        ) NOT NULL DEFAULT 'system'");
    }
};
