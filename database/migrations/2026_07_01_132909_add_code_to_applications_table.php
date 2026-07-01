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
        if (! Schema::hasColumn('applications', 'code')) {
            Schema::table('applications', function (Blueprint $table) {
                // External reference / tracking code for the application (editable by admins).
                $table->string('code')->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('applications', 'code')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }
    }
};
