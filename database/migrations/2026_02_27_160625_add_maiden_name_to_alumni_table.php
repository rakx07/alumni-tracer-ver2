<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->string('maiden_first_name', 100)->nullable()->after('full_name');
            $table->string('maiden_middle_name', 100)->nullable()->after('maiden_first_name');
            $table->string('maiden_last_name', 100)->nullable()->after('maiden_middle_name');
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropColumn(['maiden_first_name','maiden_middle_name','maiden_last_name']);
        });
    }
};