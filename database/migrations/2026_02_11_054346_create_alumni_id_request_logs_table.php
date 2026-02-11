<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni_id_request_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('request_id')->index();
            $table->unsignedBigInteger('actor_user_id')->nullable()->index();

            // Action trail
            $table->enum('action', [
                'CREATED',
                'APPROVED',
                'SET_PROCESSING',
                'DECLINED',
                'SET_READY_FOR_PICKUP',
                'RELEASED',
                'UPDATED_DETAILS',
                'UPLOADED_ATTACHMENT',
            ]);

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('alumni_id_requests')->onDelete('cascade');
            $table->foreign('actor_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_id_request_logs');
    }
};
