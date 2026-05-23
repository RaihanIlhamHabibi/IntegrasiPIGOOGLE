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
        Schema::create('google_drive_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('google_file_id')->unique();
            $table->string('file_name');
            $table->string('file_path')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('web_view_link')->nullable();
            $table->string('google_drive_folder_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_drive_files');
    }
};
