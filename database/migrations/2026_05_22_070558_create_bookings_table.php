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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('customer_profiles')->cascadeOnDelete();
        $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
        $table->date('event_date');
        $table->text('event_location');
        $table->text('notes')->nullable();
        $table->decimal('total_price', 15, 2);
        $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
