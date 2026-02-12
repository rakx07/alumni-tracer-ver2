<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('career_post_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('career_post_id')
                ->constrained('career_posts')
                ->cascadeOnDelete();

            // Storage info
            $table->string('path');                 // storage path
            $table->string('original_name');        // original filename
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();

            // Optional: for ordering files in UI
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['career_post_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_post_attachments');
    }
};
