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
            // Hapus kolom profesi (sudah ada role)
            $table->dropColumn('profesi');
            
            // Hapus index profesi jika ada
            // $table->dropIndex(['profesi']); // uncomment jika error
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenaga_kesehatan', function (Blueprint $table) {
            // Kembalikan kolom profesi
            $table->enum('profesi', ['dokter', 'bidan', 'perawat'])->after('hp');
            $table->index('profesi');
        });
    }
};
