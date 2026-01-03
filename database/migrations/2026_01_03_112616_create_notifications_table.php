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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // appointment, system, user, etc
            $table->string('title'); // Judul notifikasi
            $table->text('message'); // Pesan notifikasi
            $table->string('icon')->default('fas fa-bell'); // Icon untuk notifikasi
            $table->string('color')->default('bg-info'); // Warna background icon
            $table->unsignedBigInteger('user_id')->nullable(); // User yang menerima notifikasi (null = semua admin)
            $table->unsignedBigInteger('related_id')->nullable(); // ID terkait (appointment_id, user_id, etc)
            $table->string('related_type')->nullable(); // Model terkait (Appointment, User, etc)
            $table->string('action_url')->nullable(); // URL untuk redirect ketika diklik
            $table->boolean('is_read')->default(false); // Status baca
            $table->timestamp('read_at')->nullable(); // Waktu dibaca
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
