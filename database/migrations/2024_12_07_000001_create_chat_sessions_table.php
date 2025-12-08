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
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id'); // ID pasien
            $table->unsignedBigInteger('doctor_id'); // ID dokter
            $table->integer('message_count')->default(0); // Jumlah pesan yang dikirim pasien
            $table->boolean('is_premium')->default(false); // Apakah sudah berlangganan
            $table->boolean('is_active')->default(true); // Session masih aktif atau sudah di-end
            $table->timestamp('started_at')->nullable(); // Waktu mulai session
            $table->timestamp('ended_at')->nullable(); // Waktu end session
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            
            // Index untuk query cepat
            $table->index(['patient_id', 'doctor_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};
