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
        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        Schema::table('student_extracurriculars', function (Blueprint $table) {
            $table->ulid('extracurricular_ulid')->after('extracurricular_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extracurriculars', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });

        Schema::table('student_extracurriculars', function (Blueprint $table) {
            $table->dropColumn('extracurricular_ulid');
        });
    }
};
