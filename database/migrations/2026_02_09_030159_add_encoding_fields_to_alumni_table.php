<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            if (!Schema::hasColumn('alumni', 'encoded_by')) {
                $table->unsignedBigInteger('encoded_by')->nullable()->index();
            }
            if (!Schema::hasColumn('alumni', 'encoding_mode')) {
                $table->string('encoding_mode', 20)->default('self_service')->index(); // self_service|assisted
            }
            if (!Schema::hasColumn('alumni', 'record_status')) {
                $table->string('record_status', 30)->default('draft')->index(); // draft|submitted|validated|needs_revision
            }
            if (!Schema::hasColumn('alumni', 'validated_by')) {
                $table->unsignedBigInteger('validated_by')->nullable()->index();
            }
            if (!Schema::hasColumn('alumni', 'validated_at')) {
                $table->timestamp('validated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $drop = function($col) use ($table) {
                if (Schema::hasColumn('alumni', $col)) $table->dropColumn($col);
            };

            $drop('encoded_by');
            $drop('encoding_mode');
            $drop('record_status');
            $drop('validated_by');
            $drop('validated_at');
        });
    }
};
