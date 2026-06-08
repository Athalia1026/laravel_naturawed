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
        if (!Schema::hasColumn('bookings', 'payment_status')) {
            Schema::table('bookings', function (Blueprint $table) {
                // Buat sebagai string biasa terlebih dahulu sebagai pondasi awal sebelum di-ALTER
                $table->string('payment_status')->after('booking_status');
            });
        }

        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('unpaid','pending_verification','success', 'canceled') NOT NULL DEFAULT 'unpaid'");
        
        // Modifikasi tabel payments
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('unpaid', 'paid', 'canceled') NOT NULL DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ke struktur bawaan jika diperlukan rollback migration
        DB::statement("ALTER TABLE bookings MODIFY COLUMN payment_status ENUM('unpaid','pending_verification','success') NOT NULL DEFAULT 'unpaid'");
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('unpaid', 'paid') NOT NULL DEFAULT 'unpaid'");
    }
};