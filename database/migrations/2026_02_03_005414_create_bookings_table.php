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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->unsignedTinyInteger('players_count');
            $table->string('status');
            $table->string('source_id');
            $table->json('pricing_snapshot');
            $table->unsignedInteger('total_price');
            $table->unsignedInteger('paid_amount')->default(0);
            $table->unsignedInteger('manual_discount')->default(0);
            $table->string('manual_discount_reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('booking_code')->unique();
            $table->unsignedInteger('play_time')->nullable(); // секунды, статистика
            $table->boolean('winners')->nullable(); // победный статус, статистика
            $table->unsignedSmallInteger('hints')->nullable(); // количество подсказок, статистика
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
