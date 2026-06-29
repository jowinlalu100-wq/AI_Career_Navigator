<?php
// database/migrations/xxxx_xx_xx_create_applied_jobs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applied_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('resume_id')->nullable()->constrained('resumes')->onDelete('set null');
            $table->string('job_title');
            $table->string('company_name');
            $table->string('job_location')->nullable();
            $table->text('apply_link'); // JSearch URLs can be long, varchar(255) risks truncation
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            // Speeds up the duplicate-check query in JobController@apply.
            // Not a unique constraint — duplicate detection is handled in app code.
            $table->index(['user_id', 'job_title', 'company_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applied_jobs');
    }
};