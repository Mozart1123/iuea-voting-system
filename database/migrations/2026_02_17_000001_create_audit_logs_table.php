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
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->string('action'); // e.g., 'application_submitted', 'vote_cast', 'application_approved'
                $table->string('model_type'); // e.g., 'Application', 'Vote', 'ElectionCategory'
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('changes')->nullable(); // store what changed
                $table->ipAddress('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('timestamp');
                $table->timestamps();

                // Index for queries
                $table->index(['user_id', 'action']);
                $table->index(['model_type', 'model_id']);
                $table->index('timestamp');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
