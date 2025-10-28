<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenaga_kesehatan', function (Blueprint $table) {
            $table->id();

            // Jika nantinya dihubungkan ke users
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('foto_path')->nullable();
            $table->string('nama');
            $table->date('tanggal_lahir')->nullable();
            $table->string('email')->unique();
            $table->string('hp', 25)->nullable();
            $table->string('alumnus')->nullable();

            // Sesuai kebutuhan: dokter, bidan, perawat
            $table->enum('profesi', ['dokter', 'bidan', 'perawat']);

            $table->timestamps();
            $table->softDeletes();

            $table->index('profesi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenaga_kesehatan');
    }
};