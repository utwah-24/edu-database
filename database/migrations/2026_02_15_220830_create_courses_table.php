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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('credits')->default(3);
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('semester', ['Fall', 'Spring', 'Summer']);
            $table->string('academic_year', 10); // e.g., "2025-2026"
            $table->integer('max_students')->default(30);
            $table->enum('level', ['undergraduate', 'graduate', 'doctoral'])->default('undergraduate');
            $table->string('room')->nullable();
            $table->json('schedule')->nullable(); // Store class schedule as JSON
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
