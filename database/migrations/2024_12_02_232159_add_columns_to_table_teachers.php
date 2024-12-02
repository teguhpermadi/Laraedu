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
        Schema::table('teachers', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        Schema::table('data_teachers', function (Blueprint $table) {
            $table->ulid('teacher_ulid')->after('teacher_id');
        });

        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->ulid('teacher_ulid')->after('teacher_id');
        });

        Schema::table('teacher_grades', function (Blueprint $table) {
            $table->ulid('teacher_ulid')->after('teacher_id');
        });

        Schema::table('teacher_extracurriculars', function (Blueprint $table) {
            $table->ulid('teacher_ulid')->after('teacher_id');
        });

        Schema::table('academic_years', function (Blueprint $table) {
            $table->ulid('teacher_ulid')->after('teacher_id');
        });

        Schema::table('userables', function (Blueprint $table) {
            $table->ulid('userable_ulid')->after('userable_id');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->ulid('teacher_ulid')->after('teacher_id');
        });

        Schema::table('project_coordinators', function (Blueprint $table) {
            $table->ulid('teacher_ulid')->after('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });

        Schema::table('data_teachers', function (Blueprint $table) {
            $table->dropColumn('teacher_ulid');
        });

        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->dropColumn('teacher_ulid');
        });

        Schema::table('teacher_grades', function (Blueprint $table) {
            $table->dropColumn('teacher_ulid');
        });

        Schema::table('teacher_extracurriculars', function (Blueprint $table) {
            $table->dropColumn('teacher_ulid');
        });

        Schema::table('userables', function (Blueprint $table) {
            $table->dropColumn('userable_ulid');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('teacher_ulid');
        });

        Schema::table('project_coordinators', function (Blueprint $table) {
            $table->dropColumn('teacher_ulid');
        });
    }
};
