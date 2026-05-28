<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to check and update columns
        // First rename status to booking_status if status exists
        $checkStatus = DB::select("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME='bookings' AND COLUMN_NAME='status' AND TABLE_SCHEMA=DATABASE()
        ");

        if (!empty($checkStatus)) {
            // Status column exists, change it to booking_status
            DB::statement("
                ALTER TABLE bookings 
                CHANGE COLUMN status booking_status 
                ENUM('pending_review', 'approved', 'rejected') NOT NULL DEFAULT 'pending_review'
            ");
        }

        // Check if payment_status already exists
        $checkPaymentStatus = DB::select("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME='bookings' AND COLUMN_NAME='payment_status' AND TABLE_SCHEMA=DATABASE()
        ");

        if (empty($checkPaymentStatus)) {
            // Add payment_status column
            DB::statement("
                ALTER TABLE bookings 
                ADD COLUMN payment_status ENUM('unpaid', 'pending_verification', 'success') NOT NULL DEFAULT 'unpaid' AFTER booking_status
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if payment_status exists
        $checkPaymentStatus = DB::select("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME='bookings' AND COLUMN_NAME='payment_status' AND TABLE_SCHEMA=DATABASE()
        ");

        if (!empty($checkPaymentStatus)) {
            DB::statement("ALTER TABLE bookings DROP COLUMN payment_status");
        }

        // Check if booking_status exists
        $checkBookingStatus = DB::select("
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME='bookings' AND COLUMN_NAME='booking_status' AND TABLE_SCHEMA=DATABASE()
        ");

        if (!empty($checkBookingStatus)) {
            DB::statement("
                ALTER TABLE bookings 
                CHANGE COLUMN booking_status status 
                ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'
            ");
        }
    }
};
