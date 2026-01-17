<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumnus_id')->constrained('alumni')->cascadeOnDelete();

            // Level attended / context
            $table->enum('level', [
                'ndmu_elementary',
                'ndmu_jhs',
                'ndmu_shs',
                'ndmu_college',
                'ndmu_grad_school',
                'ndmu_law',
                'post_ndmu', // educational background after NDMU
            ])->index();

            // Common fields
            $table->string('student_number')->nullable();
            $table->year('year_entered')->nullable();
            $table->year('year_graduated')->nullable();
            $table->year('last_year_attended')->nullable();

            // College/Grad/Law
            $table->string('degree_program')->nullable();      // degree / program completed or enrolled
            $table->string('specific_program')->nullable();    // specific program/course (grad/law)
            $table->string('research_title')->nullable();      // research title (grad/law)
            $table->string('thesis_title')->nullable();        // thesis title

            // SHS
            $table->string('strand_track')->nullable();

            // Awards/honors/activities (used in elementary/jhs/shs and also college)
            $table->text('honors_awards')->nullable();
            $table->text('extracurricular_activities')->nullable();
            $table->text('clubs_organizations')->nullable();

            // Post-NDMU education
            $table->string('institution_name')->nullable();
            $table->string('institution_address')->nullable();
            $table->string('course_degree')->nullable();
            $table->year('year_completed')->nullable();
            $table->string('scholarship_award')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_educations');
    }
};
