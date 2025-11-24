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
        Schema::table('tenaga_kesehatan', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan
            $table->dropColumn(['tanggal_lahir', 'alumnus']);
            
            // Tambah kolom baru
            $table->string('str')->nullable()->after('hp'); // Surat Tanda Registrasi
            $table->string('sip')->nullable()->after('str'); // Surat Izin Praktik
            $table->text('pengalaman')->nullable()->after('sip'); // Pengalaman kerja
            $table->year('tahun_mulai')->nullable()->after('pengalaman'); // Tahun mulai praktik
            
            // Tambah role untuk tenaga kesehatan
            $table->enum('role', ['dokter_umum', 'admin', 'superadmin'])->default('dokter_umum')->after('profesi');
            
            // Jadwal shift (JSON format untuk fleksibilitas)
            // Format: [{"hari": "Senin", "jam_mulai": "08:00", "jam_selesai": "12:00"}, ...]
            $table->json('jadwal_shift')->nullable()->after('tahun_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenaga_kesehatan', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->date('tanggal_lahir')->nullable()->after('nama');
            $table->string('alumnus')->nullable()->after('hp');
            
            // Hapus kolom baru
            $table->dropColumn(['str', 'sip', 'pengalaman', 'tahun_mulai', 'role', 'jadwal_shift']);
        });
    }
};
