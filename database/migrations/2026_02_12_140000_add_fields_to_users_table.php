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
            // Add fields if they don't exist
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'faculty')) {
                $table->string('faculty')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('users', 'year_of_study')) {
                $table->integer('year_of_study')->nullable()->after('faculty');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['student', 'admin'])->default('student')->after('year_of_study');
            }
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['student_id', 'faculty', 'year_of_study', 'role', 'is_admin']);
        });
    }
};
