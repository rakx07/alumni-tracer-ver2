<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_networks', function (Blueprint $table) {
            $table->id();

            $table->string('title');                 // Required
            $table->string('link');                  // Required (FB page / website / etc.)
            $table->text('description')->nullable(); // Optional
            $table->string('logo_path')->nullable(); // storage path (public disk)

            $table->boolean('is_active')->default(true);

            // Optional manual ordering (future-proof)
            $table->unsignedInteger('sort_order')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->index(['is_active', 'title']);
            $table->index(['sort_order', 'title']);

            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_networks');
    }
};
