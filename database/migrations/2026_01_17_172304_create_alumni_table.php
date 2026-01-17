<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();

            // one intake per user (nullable if admin encodes later)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Personal info (matches all variants)
            $table->string('full_name')->index();
            $table->string('nickname')->nullable();

            $table->enum('sex', ['male','female','prefer_not'])->nullable();
            $table->date('birthdate')->nullable();
            $table->unsignedSmallInteger('age')->nullable();

            $table->enum('civil_status', ['single','married','widowed','separated'])->nullable();

            // Addresses/contacts used across variants
            $table->text('home_address')->nullable();
            $table->text('current_address')->nullable(); // some forms say "present address"
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable(); // social media account/handle
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();

            $table->timestamps();
            $table->softDeletes(); // admin soft-delete intake record
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
