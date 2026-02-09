<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('strands', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();   // STEM, HUMSS, ABM, etc.
            $table->string('name', 255);            // Full label
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strands');
    }
};
