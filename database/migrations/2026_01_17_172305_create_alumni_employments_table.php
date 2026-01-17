<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni_employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumnus_id')->constrained('alumni')->cascadeOnDelete();

            $table->string('current_status')->nullable(); // working/studying/unemployed/other (for elem/jhs forms)
            $table->string('occupation_position')->nullable();
            $table->string('company_name')->nullable();

            $table->enum('org_type', ['government','private','ngo','self_employed','other'])->nullable();

            $table->text('work_address')->nullable();
            $table->string('contact_info')->nullable(); // email/phone
            $table->string('years_of_service_or_start')->nullable();

            $table->text('licenses_certifications')->nullable();
            $table->text('achievements_awards')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_employments');
    }
};
