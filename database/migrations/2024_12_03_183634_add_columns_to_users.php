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
        Schema::table('users', function (Blueprint $table) {
            $table->ulid('ulid')->after('id');
        });

        Schema::table('userables', function (Blueprint $table) {
            $table->ulid('user_ulid')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('ulid');
        });

        Schema::table('userables', function (Blueprint $table) {
            $table->dropColumn('user_ulid');
        });
    }
};
