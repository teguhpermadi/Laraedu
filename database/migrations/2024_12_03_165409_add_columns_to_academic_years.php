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
        Schema::table('academic_years', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('attitudes', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('project_coordinators', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('project_notes', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('project_students', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('student_extracurriculars', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('student_grade', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('teacher_extracurriculars', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('teacher_grades', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });

        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->ulid('academic_year_ulid')->after('academic_year_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('attitudes', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('project_coordinators', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('project_notes', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('project_students', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('student_extracurriculars', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('student_grade', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('teacher_extracurriculars', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('teacher_grades', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });

        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->dropColumn('academic_year_ulid');
        });
    }
};
