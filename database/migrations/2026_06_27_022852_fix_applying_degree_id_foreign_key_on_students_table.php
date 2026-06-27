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
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['applying_degree_id']);
            $table->foreign('applying_degree_id')->references('id')->on('degrees')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['applying_degree_id']);
            $table->foreign('applying_degree_id')->references('id')->on('programs')->onDelete('set null');
        });
    }
};
