<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumnus_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index(); // editor
            $table->string('action', 50)->default('update'); // create|update|submit|validate|return|link_user|update_user
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();

            // optional FKs (safe if you want). Comment out if you prefer no constraints.
            // $table->foreign('alumnus_id')->references('id')->on('alumni')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_audits');
    }
};
