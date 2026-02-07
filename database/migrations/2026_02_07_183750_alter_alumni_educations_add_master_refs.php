<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('alumni_educations', function (Blueprint $table) {

            // YES/NO per your new requirements
            if (!Schema::hasColumn('alumni_educations', 'did_graduate')) {
                $table->boolean('did_graduate')->nullable()->after('level');
            }

            // Strand reference for SHS (pre-listed)
            if (!Schema::hasColumn('alumni_educations', 'strand_id')) {
                // put it near existing strand_track for readability
                $table->unsignedBigInteger('strand_id')->nullable()->after('strand_track');
            }

            // Program reference for College/Grad/Law (pre-listed)
            if (!Schema::hasColumn('alumni_educations', 'program_id')) {
                // put it near existing degree_program for readability
                $table->unsignedBigInteger('program_id')->nullable()->after('degree_program');
            }
        });

        // Add FKs in a separate Schema::table to avoid issues on some MySQL versions
        Schema::table('alumni_educations', function (Blueprint $table) {
            if (Schema::hasColumn('alumni_educations', 'strand_id')) {
                $table->foreign('strand_id')
                    ->references('id')->on('strands')
                    ->nullOnDelete();
            }

            if (Schema::hasColumn('alumni_educations', 'program_id')) {
                $table->foreign('program_id')
                    ->references('id')->on('programs')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumni_educations', function (Blueprint $table) {

            if (Schema::hasColumn('alumni_educations', 'strand_id')) {
                $table->dropForeign(['strand_id']);
                $table->dropColumn('strand_id');
            }

            if (Schema::hasColumn('alumni_educations', 'program_id')) {
                $table->dropForeign(['program_id']);
                $table->dropColumn('program_id');
            }

            if (Schema::hasColumn('alumni_educations', 'did_graduate')) {
                $table->dropColumn('did_graduate');
            }
        });
    }
};
