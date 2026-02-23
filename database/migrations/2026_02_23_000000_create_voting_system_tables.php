<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // super_admin, system_admin, normal_admin
            $table->string('display_name');
            $table->timestamps();
        });

        // 2. Add role_id to Users (Adjusting base table)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
        });

        // 3. Categories (Election Positions)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Guild President, Faculty Representative
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('faculty_restriction')->nullable(); // null means open to all
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Candidates
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->string('image_path')->nullable();
            $table->text('manifesto')->nullable();
            $table->string('faculty')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Voters (Activity Record - No accounts)
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('full_name');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('voted_at')->useCurrent();
            $table->timestamps();
        });

        // 6. Votes (Atomic Record)
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('integrity_hash')->nullable(); // SHA-256 of (voter_id + category_id + candidate_id + seed)
            $table->timestamps();
        });

        // 7. Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Admin who did the action
            $table->string('action'); // e.g., 'CREATE_CANDIDATE', 'UPDATE_SETTINGS'
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        // 8. System Settings
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('votes');
        Schema::dropIfExists('voters');
        Schema::dropIfExists('candidates');
        Schema::dropIfExists('categories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
        Schema::dropIfExists('roles');
    }
};
