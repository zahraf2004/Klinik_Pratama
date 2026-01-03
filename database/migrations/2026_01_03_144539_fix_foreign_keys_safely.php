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
        // Cara sederhana: coba tambahkan foreign key, jika error berarti sudah ada
        
        try {
            // Untuk tabel notifications
            if (Schema::hasTable('notifications')) {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            }
        } catch (\Exception $e) {
            // Foreign key sudah ada, skip
        }

        try {
            // Untuk tabel activity_logs
            if (Schema::hasTable('activity_logs')) {
                Schema::table('activity_logs', function (Blueprint $table) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                });
            }
        } catch (\Exception $e) {
            // Foreign key sudah ada, skip
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback jika diperlukan
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        if (Schema::hasTable('activity_logs')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }
    }
};
