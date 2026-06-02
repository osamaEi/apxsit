<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('conversation_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['conversation_id', 'student_id']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            // Make user_id nullable since messages can now be from students
            $table->foreignId('user_id')->nullable()->change();
        });

        Schema::table('message_reads', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            // Make user_id nullable since reads can now be from students
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_student');
    }
};
