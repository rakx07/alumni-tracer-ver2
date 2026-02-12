<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('career_posts', function (Blueprint $table) {
            $table->id();

            // Basic job post fields
            $table->string('title');
            $table->string('company')->nullable();
            $table->string('location')->nullable();

            // Full-time, Part-time, Contract, Internship, etc.
            $table->string('employment_type')->nullable();

            // Preview text for list cards
            $table->string('summary', 300)->nullable();

            // Main content (optional)
            $table->longText('description')->nullable();
            $table->longText('how_to_apply')->nullable();

            // Application destination (optional)
            $table->string('apply_url')->nullable();
            $table->string('apply_email')->nullable();

            // Timeframe (optional)
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Publish toggle (optional control)
            $table->boolean('is_published')->default(true);

            // who created it
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->timestamps();

            // indexing for list filtering
            $table->index(['is_published', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_posts');
    }
};
