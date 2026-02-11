<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Make is_active_request nullable (so inactive can be NULL)
        Schema::table('alumni_id_requests', function (Blueprint $table) {
            $table->tinyInteger('is_active_request')->nullable()->change();
        });

        // 2) Convert existing inactive 0 to NULL to avoid duplicate (alumnus_id,0)
        DB::table('alumni_id_requests')
            ->where('is_active_request', 0)
            ->update(['is_active_request' => null]);

        // 3) Recreate unique index safely (drop if exists, then add)
        Schema::table('alumni_id_requests', function (Blueprint $table) {
            // dropIndex uses the actual index name
            $table->dropUnique('uniq_alumnus_one_active_request');
        });

        Schema::table('alumni_id_requests', function (Blueprint $table) {
            $table->unique(['alumnus_id', 'is_active_request'], 'uniq_alumnus_one_active_request');
        });
    }

    public function down(): void
    {
        Schema::table('alumni_id_requests', function (Blueprint $table) {
            $table->dropUnique('uniq_alumnus_one_active_request');
        });

        // revert NULL back to 0 (optional)
        DB::table('alumni_id_requests')
            ->whereNull('is_active_request')
            ->update(['is_active_request' => 0]);

        Schema::table('alumni_id_requests', function (Blueprint $table) {
            $table->tinyInteger('is_active_request')->default(0)->nullable(false)->change();
            $table->unique(['alumnus_id', 'is_active_request'], 'uniq_alumnus_one_active_request');
        });
    }
};
