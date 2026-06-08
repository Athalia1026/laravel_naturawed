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
        // 🌿 PROTEKSI ACID: Hanya tambah kolom jika kolom tersebut BELUM ada di database
        if (!Schema::hasColumn('bookings', 'estimated_guests')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->integer('estimated_guests')->nullable()->after('event_location');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 🌿 PROTEKSI ACID: Hanya hapus jika kolom tersebut MEMANG ada di database
        if (Schema::hasColumn('bookings', 'estimated_guests')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('estimated_guests');
            });
        }
    }
};