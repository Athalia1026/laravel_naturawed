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
        // 1. Cek dan ubah nama kolom status menjadi booking_status
        if (Schema::hasColumn('bookings', 'status')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->renameColumn('status', 'booking_status');
            });
        }

        // 2. Cek dan tambahkan kolom payment_status
        if (!Schema::hasColumn('bookings', 'payment_status')) {
            Schema::table('bookings', function (Blueprint $table) {
                // Menggunakan string lebih aman untuk SQLite dibanding ENUM
                $table->string('payment_status')->default('unpaid')->after('booking_status');
            });
        }
    }    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Hapus kolom payment_status jika ada
        if (Schema::hasColumn('bookings', 'payment_status')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
        }

        // 2. Kembalikan booking_status menjadi status dengan aman
        if (Schema::hasColumn('bookings', 'booking_status')) {
            // TAHAP A: Terjemahkan data baru kembali ke data lama menggunakan Query Builder
            DB::table('bookings')->where('booking_status', 'pending_review')->update(['booking_status' => 'pending']);
            DB::table('bookings')->where('booking_status', 'approved')->update(['booking_status' => 'confirmed']);
            DB::table('bookings')->where('booking_status', 'rejected')->update(['booking_status' => 'cancelled']);

            // TAHAP B: Kembalikan nama kolomnya
            Schema::table('bookings', function (Blueprint $table) {
                $table->renameColumn('booking_status', 'status');
            });
        }
    }
};
