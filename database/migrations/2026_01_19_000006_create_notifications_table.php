<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('borrowing_id')->nullable()->constrained('borrowings')->onDelete('set null');
            $table->enum('type', ['due_soon', 'overdue', 'returned', 'available', 'new_book', 'system'])->default('system');
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->json('data')->nullable(); // Store additional data
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('borrowing_id');
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
