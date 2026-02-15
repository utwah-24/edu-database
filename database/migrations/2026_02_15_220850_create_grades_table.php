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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->string('assignment_name');
            $table->enum('assignment_type', ['homework', 'quiz', 'midterm', 'final', 'project', 'participation'])->default('homework');
            $table->decimal('grade', 5, 2);
            $table->decimal('max_grade', 5, 2)->default(100);
            $table->decimal('weight', 5, 2)->default(10); // Percentage weight in final grade
            $table->date('grade_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
