<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni_id_requests', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to intake record (alumni table)
            $table->unsignedBigInteger('alumnus_id')->index();

            // Snapshot fields (from intake / user input)
            $table->string('school_id')->nullable(); // optional (student number)
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();

            // From intake/education snapshot (best available)
            $table->string('course')->nullable();              // from alumni_educations.degree_program or specific_program
            $table->unsignedSmallInteger('grad_month')->nullable(); // optional if you later add month
            $table->unsignedSmallInteger('grad_year')->nullable();  // from alumni_educations.year_graduated

            $table->date('birthdate')->nullable(); // from alumni.birthdate

            // Request type
            $table->enum('request_type', ['NEW', 'LOST', 'STOLEN', 'BROKEN'])->default('NEW');

            // Signature upload (required)
            $table->string('signature_path'); // storage path

            // Workflow status
            $table->enum('status', [
                'PENDING',
                'APPROVED',
                'PROCESSING',
                'DECLINED',
                'READY_FOR_PICKUP',
                'RELEASED',
            ])->default('PENDING')->index();

            // Enforce "one active at a time"
            // Active = 1 while status is not DECLINED nor RELEASED (we control this in code)
            $table->tinyInteger('is_active_request')->default(1)->index();

            // Officer remarks (esp. for decline)
            $table->text('remarks')->nullable();

            // Timestamps for key steps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('processing_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('declined_at')->nullable();

            // Who last updated status (Officer / IT Admin)
            $table->unsignedBigInteger('last_acted_by')->nullable()->index();

            $table->timestamps();

            // FK constraints
            $table->foreign('alumnus_id')->references('id')->on('alumni')->onDelete('cascade');
            $table->foreign('last_acted_by')->references('id')->on('users')->nullOnDelete();

            // Only one active request per alumnus
            $table->unique(['alumnus_id', 'is_active_request'], 'uniq_alumnus_one_active_request');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_id_requests');
    }
};
