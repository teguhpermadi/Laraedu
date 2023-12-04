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
        Schema::table('teacher_grades', function (Blueprint $table) {
            $table->enum('curriculum', ['merdeka', '2013'])->default('merdeka')->after('grade_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_grades', function (Blueprint $table) {
            $table->dropColumn('curriculum');
        });
    }
};
