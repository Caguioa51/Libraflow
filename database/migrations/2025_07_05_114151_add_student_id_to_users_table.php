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
        // Only add the column if it does not already exist
        if (!Schema::hasColumn('users', 'student_id')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('student_id')->nullable()->unique();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the column if it exists
        if (Schema::hasColumn('users', 'student_id')) {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('student_id');
        });
        }
    }
};
