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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Başlık
            $table->string('slug')->unique(); // Slug
            $table->text('content'); // İçerik
            $table->string('cover_image')->nullable(); // Kapak Görseli
            $table->dateTime('published_at')->nullable(); // Yayın Tarihi
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Yazarm
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
