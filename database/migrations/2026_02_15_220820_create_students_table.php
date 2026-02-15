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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id')->unique();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_phone');
            $table->string('guardian_email')->nullable();
            $table->date('admission_date');
            $table->enum('enrollment_status', ['active', 'inactive', 'graduated', 'suspended'])->default('active');
            $table->string('blood_group')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
