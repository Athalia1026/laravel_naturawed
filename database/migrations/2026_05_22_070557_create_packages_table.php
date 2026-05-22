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
    Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vendor_id')->constrained('vendor_profiles')->cascadeOnDelete();
        $table->foreignId('category_id')->constrained('categories'); // Tanpa cascade delete agar histori aman
        $table->string('package_name', 150);
        $table->decimal('price', 15, 2);
        $table->string('duration', 50)->nullable();
        $table->text('description')->nullable();
        $table->string('main_image', 255)->nullable();
        $table->text('features')->nullable();
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
