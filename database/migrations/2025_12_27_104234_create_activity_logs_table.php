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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // Aksi yang dilakukan (create, update, delete, approve, etc)
            $table->string('model'); // Model yang diakses (Appointment, TenagaKesehatan, Obat, etc)
            $table->string('model_id')->nullable(); // ID dari model yang diakses
            $table->string('description'); // Deskripsi aktivitas
            $table->unsignedBigInteger('user_id')->nullable(); // User yang melakukan aksi
            $table->json('old_values')->nullable(); // Nilai lama (untuk update/delete)
            $table->json('new_values')->nullable(); // Nilai baru (untuk create/update)
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
