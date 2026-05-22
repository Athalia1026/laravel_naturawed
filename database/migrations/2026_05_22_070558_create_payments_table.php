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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
        $table->string('payment_method', 50)->nullable();
        $table->decimal('amount', 15, 2);
        $table->string('payment_proof', 255)->nullable();
        $table->enum('status', ['unpaid', 'pending_verification', 'paid', 'failed'])->default('unpaid');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
