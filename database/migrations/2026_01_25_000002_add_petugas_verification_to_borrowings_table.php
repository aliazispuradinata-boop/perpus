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
            $table->timestamp('confirmed_at')->nullable()->after('returned_at')->comment('Waktu konfirmasi pengambilan oleh petugas');
            $table->unsignedBigInteger('confirmed_by_petugas_id')->nullable()->after('confirmed_at')->comment('ID petugas yang mengkonfirmasi pengambilan');
            $table->unsignedBigInteger('verified_by_petugas_id')->nullable()->after('confirmed_by_petugas_id')->comment('ID petugas yang memverifikasi pengembalian');
            
            // Foreign keys
            $table->foreign('confirmed_by_petugas_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by_petugas_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropForeignKey('borrowings_confirmed_by_petugas_id_foreign');
            $table->dropForeignKey('borrowings_verified_by_petugas_id_foreign');
            $table->dropColumn(['confirmed_at', 'confirmed_by_petugas_id', 'verified_by_petugas_id']);
        });
    }
};
