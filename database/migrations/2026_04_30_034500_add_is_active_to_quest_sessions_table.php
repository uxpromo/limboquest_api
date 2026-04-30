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
        Schema::table('quest_sessions', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('pricing_rule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quest_sessions', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
