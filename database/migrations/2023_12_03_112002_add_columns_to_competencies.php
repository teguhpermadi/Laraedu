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
            $table->dropColumn('category');
            $table->string('code_skill')->unique()->nullable()->after('description');
            $table->string('description_skill')->nullable()->after('code_skill');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competencies', function (Blueprint $table) {
            $table->enum('category', ['pengetahuan', 'keterampilan'])->nullable();
            $table->dropColumn(['code_skill', 'description_skill']);
        });
    }
};
