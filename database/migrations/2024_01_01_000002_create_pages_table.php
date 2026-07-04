<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('slug');
            $table->longText('content');
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('status')->default('draft');
            $table->string('template')->nullable();
            $table->text('featured_image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
