<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();

            // college | grad_school | law
            $table->string('category', 50);

            // optional short code (BSIT, MBA, JD, etc.)
            $table->string('code', 50)->nullable();

            $table->string('name', 255);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['category', 'is_active']);

            // ensure no duplicate codes per category (code can be null)
            $table->unique(['category', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
