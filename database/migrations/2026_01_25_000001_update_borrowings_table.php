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
        Schema::table('borrowings', function (Blueprint $table) {
            // Add new columns
            $table->string('qr_code')->nullable()->after('notes');
            $table->integer('duration_days')->default(14)->after('due_date');
            $table->text('fine_notes')->nullable()->after('qr_code');
            
            // Change status enum to include 'pending' status
            $table->dropColumn('status');
        });
        
        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'returned', 'overdue', 'lost'])->default('pending')->after('duration_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'duration_days', 'fine_notes']);
            $table->dropColumn('status');
        });
        
        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('status', ['active', 'returned', 'overdue', 'lost'])->default('active');
        });
    }
};
