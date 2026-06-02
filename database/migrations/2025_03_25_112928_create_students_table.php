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
            
            // Basic Information
            $table->string('academic_year')->nullable();
            $table->unsignedBigInteger('study_country_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->boolean('is_transfer')->default(false);
            
            // Passport Information
            $table->date('date_of_birth')->nullable();
            $table->string('passport_id')->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->boolean('needs_visa_support')->default(false);
            
            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->unsignedBigInteger('country_of_residence_id')->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            
            // Family Information
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('emergency_email')->nullable();
            $table->string('emergency_phone')->nullable();
            
            // Education Information
            $table->unsignedBigInteger('applying_degree_id')->nullable();
            $table->string('high_school_name')->nullable();
            $table->unsignedBigInteger('high_school_country_id')->nullable();
            $table->string('gpa')->nullable();
            
            // Document Paths
            $table->string('photo_path')->nullable();
            $table->string('passport_path')->nullable();
            $table->string('transcript_path')->nullable();
            $table->string('diploma_path')->nullable();
            $table->string('denklik_path')->nullable();
            $table->string('certificate_path')->nullable();
            $table->string('other_documents_path')->nullable();
            
            // Status and Meta
            $table->string('status')->default('New');
            $table->text('notes')->nullable();
            $table->date('application_date')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('study_country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('country_of_residence_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('nationality_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('high_school_country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('applying_degree_id')->references('id')->on('programs')->onDelete('set null');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
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
