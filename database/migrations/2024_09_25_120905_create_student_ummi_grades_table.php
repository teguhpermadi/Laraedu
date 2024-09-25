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
        Schema::create('student_ummi_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->references('id')->on('academic_years')->cascadeOnDelete();
            $table->foreignId('ummi_grade_id')->references('id')->on('ummi_grades')->cascadeOnDelete();
            $table->foreignId('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['academic_year_id', 'ummi_grade_id', 'student_id'], 'student_ummi_grades_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_ummi_grades');
    }
};
