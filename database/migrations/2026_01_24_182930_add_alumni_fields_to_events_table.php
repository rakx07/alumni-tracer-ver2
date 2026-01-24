<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Basics
            $table->string('type')->nullable()->after('title'); // reunion, webinar, outreach, etc.
            $table->string('organizer')->nullable()->after('type'); // e.g. Office of Alumni Relations
            $table->string('target_group')->nullable()->after('organizer'); // e.g. Batch 2010, CBA Alumni
            $table->string('audience')->nullable()->after('target_group'); // e.g. Alumni only / Open to public

            // Schedule
            $table->dateTime('start_at')->nullable()->after('description');
            $table->dateTime('end_at')->nullable()->after('start_at');

            // Venue/links
            $table->string('venue')->nullable()->after('end_at');
            $table->string('meeting_link')->nullable()->after('venue'); // for online events

            // Registration
            $table->boolean('requires_registration')->default(false)->after('meeting_link');
            $table->string('registration_link')->nullable()->after('requires_registration');
            $table->unsignedInteger('capacity')->nullable()->after('registration_link');

            // Contact
            $table->string('contact_person')->nullable()->after('capacity');
            $table->string('contact_email')->nullable()->after('contact_person');
            $table->string('contact_phone')->nullable()->after('contact_email');

            // Media
            $table->string('poster_path')->nullable()->after('contact_phone');

            // Publishing/visibility
            $table->boolean('is_featured')->default(false)->after('poster_path');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'type','organizer','target_group','audience',
                'start_at','end_at','venue','meeting_link',
                'requires_registration','registration_link','capacity',
                'contact_person','contact_email','contact_phone',
                'poster_path','is_featured',
            ]);
        });
    }
};
