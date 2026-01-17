<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alumni_engagement_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumnus_id')->constrained('alumni')->cascadeOnDelete();

            // interests (checkboxes in your doc)
            $table->boolean('willing_contacted')->default(false);
            $table->boolean('willing_events')->default(false);
            $table->boolean('willing_mentor')->default(false);
            $table->boolean('willing_donation')->default(false);
            $table->boolean('willing_scholarship')->default(false);

            // preferred updates channel (doc mentions email/sms/facebook/text)
            $table->boolean('prefer_email')->default(false);
            $table->boolean('prefer_sms')->default(false);
            $table->boolean('prefer_facebook')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_engagement_preferences');
    }
};
