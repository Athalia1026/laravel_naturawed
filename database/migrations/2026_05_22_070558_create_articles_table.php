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
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('journalist_id')->constrained('users')->cascadeOnDelete();
        $table->string('title', 255);
        $table->string('author_name', 100);
        $table->string('category', 50);
        $table->text('image_url');
        $table->longText('content');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
