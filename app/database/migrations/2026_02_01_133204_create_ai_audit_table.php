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
        Schema::create('ai_request_audits', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // openai, anthropic, gemini
            $table->string('model');
            $table->text('prompt');
            $table->text('response')->nullable();
            $table->integer('tokens_used')->default(0);
            $table->integer('prompt_tokens')->nullable();
            $table->integer('completion_tokens')->nullable();
            $table->string('status'); // success, failed, fallback
            $table->text('error_message')->nullable();
            $table->foreignId('question_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('cost', 10, 6)->nullable(); // Optional: track costs
            $table->integer('response_time_ms')->nullable(); // Response time in milliseconds
            $table->timestamps();

            // Indexes for common queries
            $table->index('provider');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_request_audits');
    }
};
