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
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            // $table->foreignId('grade_id')->constrained()->onDelete('cascade');
            // $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            // $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            // $table->string('passing_grade')->nullable();
            $table->foreignId('academic_year_id')->references('id')->on('academic_years')->cascadeOnDelete();
            $table->foreignId('grade_id')->references('id')->on('grades')->cascadeOnDelete();
            $table->foreignId('teacher_id')->references('id')->on('teachers')->cascadeOnDelete();
            $table->foreignId('subject_id')->references('id')->on('subjects')->cascadeOnDelete();

            // $table->timestamps();

            // Tambahkan indeks unik pada kombinasi kolom yang diperlukan
            $table->unique(['academic_year_id', 'grade_id', 'teacher_id', 'subject_id'], 'teacher_subject_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subjects');
    }
};
