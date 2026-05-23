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
        Schema::create('google_calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('google_event_id')->unique();
            $table->string('event_title');
            $table->text('event_description')->nullable();
            $table->dateTime('event_start');
            $table->dateTime('event_end');
            $table->string('location')->nullable();
            $table->string('calendar_id')->nullable();
            $table->string('hangout_link')->nullable();
            $table->string('status')->default('confirmed');
            $table->boolean('all_day')->default(false);
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_calendar_events');
    }
};
