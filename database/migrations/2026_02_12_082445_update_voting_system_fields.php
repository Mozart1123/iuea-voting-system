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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('status')->default('closed')->after('is_active'); // nomination, voting, closed
            $table->dateTime('start_time')->nullable()->after('status');
            $table->dateTime('end_time')->nullable()->after('start_time');
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('category_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('approved')->after('position_number'); // pending, approved, rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['status', 'start_time', 'end_time']);
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status']);
        });
    }
};
