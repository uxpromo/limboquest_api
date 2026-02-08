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
        Schema::create('quests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('playtime')->nullable(); // e.g. "1 hour"
            $table->unsignedTinyInteger('players_min')->nullable();
            $table->unsignedTinyInteger('players_max')->nullable();
            $table->unsignedTinyInteger('players_base_limit')->nullable();
            $table->unsignedInteger('surcharge_price')->default(0);
            $table->unsignedInteger('base_price')->default(0);
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('age_rating')->nullable(); // e.g. "12+", "18+"
            $table->boolean('is_visible')->default(false);
            $table->boolean('is_in_dev')->default(false);
            $table->string('opening_date_text')->nullable(); // e.g. "spring 2026"
            $table->unsignedInteger('average_time')->nullable(); // seconds, manual
            $table->boolean('is_auto_average_time')->default(true);
            $table->unsignedTinyInteger('passability')->nullable(); // 0-100, manual
            $table->boolean('is_auto_passability')->default(true);
            $table->unsignedInteger('best_time')->nullable(); // seconds, manual
            $table->boolean('is_auto_best_time')->default(true);
            $table->unsignedTinyInteger('difficulty_level')->nullable();
            $table->unsignedTinyInteger('scariness_level')->nullable();
            $table->boolean('is_bookable')->default(true);
            $table->unsignedSmallInteger('sort')->default(999);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quests');
    }
};
