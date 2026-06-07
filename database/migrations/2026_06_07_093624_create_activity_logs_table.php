<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke ID User yang melakukan aksi (index otomatis)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Deskripsi aktivitas singkat (contoh: 'Membayar Pesanan', 'Menyetujui Booking')
            $table->string('activity'); 
            
            // Nama tabel terkait (contoh: 'payments', 'bookings')
            $table->string('table_name')->nullable(); 
            
            // ID dari baris tabel terkait (contoh: booking_id atau payment_id)
            $table->unsignedBigInteger('record_id')->nullable(); 
            
            // Catatan detail tambahan dalam bentuk teks/JSON jika diperlukan
            $table->text('details')->nullable(); 
            
            // Alamat IP User untuk kebutuhan keamanan audit trail
            $table->string('ip_address', 45)->nullable(); 
            
            // Menggunakan timestamp standar pencatatan waktu
            $table->timestamps();

            // 🌿 OPTIMASI: Menambahkan index gabungan untuk mempercepat query log di dashboard admin/vendor
            $table->index(['table_name', 'record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};