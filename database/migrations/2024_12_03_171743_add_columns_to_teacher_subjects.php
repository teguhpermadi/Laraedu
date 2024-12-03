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
        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        Schema::table('competencies', function (Blueprint $table) {
            $table->ulid('teacher_subject_ulid')->after('teacher_subject_id');
        });

        Schema::table('student_competencies', function (Blueprint $table) {
            $table->ulid('teacher_subject_ulid')->after('teacher_subject_id');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->ulid('teacher_subject_ulid')->after('teacher_subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_subjects', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });

        Schema::table('competencies', function (Blueprint $table) {
            $table->dropColumn('teacher_subject_ulid');
        });

        Schema::table('student_competencies', function (Blueprint $table) {
            $table->dropColumn('teacher_subject_ulid');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn('teacher_subject_ulid');
        });
    }
};
