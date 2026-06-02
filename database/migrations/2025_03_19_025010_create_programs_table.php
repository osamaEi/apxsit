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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('university_id');
            $table->string('department');
            $table->string('degree')->default('Bachelor');  // Bachelor, Master, PhD
            $table->string('language')->default('English');
            $table->decimal('before_discount', 10, 2);
            $table->decimal('after_discount', 10, 2);
            $table->decimal('cash_discount', 10, 2)->default(0);
            $table->decimal('deposit_payment', 10, 2)->default(0);
            $table->string('shift_type')->default('Day');  // Day, Evening, etc.
            $table->decimal('siblings_discount', 5, 2)->default(0);
            $table->decimal('brothers_discount', 5, 2)->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
