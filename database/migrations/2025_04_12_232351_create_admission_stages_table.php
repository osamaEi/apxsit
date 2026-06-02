<?php

use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admission_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // To define the sequence of stages
            $table->string('color')->nullable(); // For UI representation
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial stages based on Application::STATUSES
        $this->seedStages();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_stages');
    }

    /**
     * Seed the stages from the Application model constant
     */
    private function seedStages()
    {
        $statuses = Application::STATUSES;
        $order = 1;
        
        foreach ($statuses as $key => $value) {
            DB::table('admission_stages')->insert([
                'name' => $value,
                'description' => "Stage for $value applications",
                'order' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};