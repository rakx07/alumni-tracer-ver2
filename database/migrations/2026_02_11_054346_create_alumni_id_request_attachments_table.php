<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni_id_request_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('request_id')->index();

            // Required attachments depending on request_type:
            // LOST/STOLEN => affidavit
            // BROKEN => proof
            $table->enum('attachment_type', ['AFFIDAVIT_LOSS', 'BROKEN_PROOF']);
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable()->index();

            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('alumni_id_requests')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();

            // Prevent duplicate attachment types per request
            $table->unique(['request_id', 'attachment_type'], 'uniq_request_attachment_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_id_request_attachments');
    }
};
