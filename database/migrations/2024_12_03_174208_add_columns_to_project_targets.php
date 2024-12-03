<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_targets', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        Schema::table('project_students', function (Blueprint $table) {
            $table->ulid('project_target_ulid')->after('project_target_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_targets', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });

        Schema::table('project_students', function (Blueprint $table) {
            $table->dropColumn('project_target_ulid');
        });
    }
};
