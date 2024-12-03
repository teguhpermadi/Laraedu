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
        Schema::table('competencies', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        Schema::table('student_competencies', function (Blueprint $table) {
            $table->ulid('competency_ulid')->after('competency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competencies', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });

        Schema::table('student_competencies', function (Blueprint $table) {
            $table->dropColumn('competency_ulid');
        });
    }
};
