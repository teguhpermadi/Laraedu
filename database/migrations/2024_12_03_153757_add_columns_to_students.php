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
        Schema::table('students', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        Schema::table('data_students', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

        Schema::table('student_grade', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

        Schema::table('student_competencies', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

        Schema::table('student_extracurriculars', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

        Schema::table('attitudes', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

        Schema::table('project_notes', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

        Schema::table('project_students', function (Blueprint $table) {
            $table->ulid('student_ulid')->after('student_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });

        Schema::table('data_students', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });

        Schema::table('student_grades', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });

        Schema::table('student_competencies', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });

        Schema::table('student_extracurriculars', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });

        Schema::table('attitudes', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });

        Schema::table('project_notes', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });

        Schema::table('project_students', function (Blueprint $table) {
            $table->dropColumn('student_ulid');
        });
    }
};
